<?php

namespace App\Http\Controllers\Alibnhamze;

use App\Enums\OtpType;
use App\Exceptions\OtpSendingFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Alibnhamze\StorePreRegistrationRequest;
use App\Http\Requests\Alibnhamze\VerifyPreRegistrationOtpRequest;
use App\Models\PreRegistration;
use App\Services\OtpService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PreRegistrationController extends Controller
{
    private const SESSION_KEY = 'pre_registration.pending';
    private const SUCCESS_KEY = 'pre_registration_success';
    private const OTP_LENGTH = 6;
    private const OTP_TTL_MINUTES = 2;
    private const RESEND_COOLDOWN_SECONDS = 120;
    private const MAX_ATTEMPTS = 5;
    private const LOCAL_TEST_CODE = '123465';

    public function __construct(private readonly OtpService $otpService) {}

    private function isLocalTestMode(): bool
    {
        return app()->environment('local');
    }

    public function create(): View
    {
        return view('alibnhamze.auth.pre-register', ['step' => 'form']);
    }


    public function store(StorePreRegistrationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (PreRegistration::query()
            ->where('national_code', $data['national_code'])
            ->exists()) {
            return back()
                ->withErrors([
                    'national_code' => 'این کد ملی قبلاً در سامانه پیش‌ثبت‌نام شده است.',
                ])
                ->withInput();
        }

        $previous = $request->session()->get(self::SESSION_KEY);

        if (
            $previous &&
            ($previous['phone'] ?? null) === $data['phone_number'] &&
            $this->resendAvailableIn($previous) > 0
        ) {
            return redirect()
                ->route('pre-register.otp.form')
                ->withErrors([
                    'code' => 'کد قبلی هنوز فعال است. لطفاً منتظر بمانید.',
                ]);
        }

        try {
            // Always generate and persist OTP, even in local mode.
            $this->otpService->issue(
                $data['phone_number'],
                OtpType::PRE_REGISTER,
                [
                    'code' => app()->environment('local') ? '123456' : null,
                    'length' => self::OTP_LENGTH,
                    'ttl_minutes' => self::OTP_TTL_MINUTES,
                ]
            );
        } catch (OtpSendingFailedException $exception) {
            return back()
                ->withErrors([
                    'phone_number' => $exception->getMessage(),
                ])
                ->withInput();
        }

        if (
            $previous &&
            ($previous['phone'] ?? null) !== $data['phone_number']
        ) {
            $this->otpService->invalidate(
                $previous['phone'],
                OtpType::PRE_REGISTER
            );
        }

        $request->session()->put(self::SESSION_KEY, [
            'data' => $data,
            'phone' => $data['phone_number'],
            'attempts' => 0,
            'otp_sent_at' => now()->timestamp,
        ]);

        return redirect()
            ->route('pre-register.otp.form')
            ->with(
                'success',
                app()->environment('local')
                    ? 'کد آزمایشی ۱۲۳۴۵۶ ایجاد شد.'
                    : 'کد تأیید به شماره همراه شما ارسال شد.'
            );
    }

    public function showOtpForm(Request $request): View|RedirectResponse
    {
        $pending = $request->session()->get(self::SESSION_KEY);

        if (! $pending) {
            return redirect()
                ->route('pre-register.create')
                ->withErrors(['phone_number' => 'برای تأیید شماره، ابتدا فرم پیش‌ثبت‌نام را تکمیل کنید.']);
        }

        return view('alibnhamze.auth.pre-register', [
            'step' => 'otp',
            'phone' => $pending['phone'],
            'resendAvailableIn' => $this->resendAvailableIn($pending),
            'otpTtl' => self::OTP_TTL_MINUTES * 60,
        ]);
    }


    public function verifyOtp(VerifyPreRegistrationOtpRequest $request): RedirectResponse
    {
        $pending = $request->session()->get(self::SESSION_KEY);

        if (! $pending) {
            return redirect()
                ->route('pre-register.create')
                ->withErrors([
                    'phone_number' => 'نشست پیش‌ثبت‌نام شما منقضی شده است. لطفاً فرم را دوباره تکمیل کنید.',
                ]);
        }

        $phone = $pending['phone'];
        $code = (string) $request->validated()['code'];

        // ================= MAX ATTEMPTS =================
        if ((int) ($pending['attempts'] ?? 0) >= self::MAX_ATTEMPTS) {
            $this->otpService->invalidate($phone, OtpType::PRE_REGISTER);
            $request->session()->forget(self::SESSION_KEY);

            return redirect()
                ->route('pre-register.create')
                ->withErrors([
                    'code' => 'تعداد تلاش‌های ناموفق بیش از حد مجاز شد. لطفاً دوباره کد دریافت کنید.',
                ]);
        }

        // ================= VERIFY OTP FROM DATABASE =================
        // Even in local mode, verify the stored OTP.
        $otp = $this->otpService->find(
            $phone,
            OtpType::PRE_REGISTER,
            $code
        );

        if (! $otp) {
            $pending['attempts'] = (int) ($pending['attempts'] ?? 0) + 1;
            $request->session()->put(self::SESSION_KEY, $pending);

            $latest = $this->otpService->latest(
                $phone,
                OtpType::PRE_REGISTER
            );

            $expired = $latest
                && ! $latest->isConsumed()
                && $latest->isExpired();

            // Revoke the OTP after too many failed attempts.
            if ($pending['attempts'] >= self::MAX_ATTEMPTS) {
                $this->otpService->invalidate(
                    $phone,
                    OtpType::PRE_REGISTER
                );

                $request->session()->forget(self::SESSION_KEY);

                return redirect()
                    ->route('pre-register.create')
                    ->withErrors([
                        'code' => 'تعداد تلاش‌های ناموفق بیش از حد مجاز شد. لطفاً دوباره کد دریافت کنید.',
                    ]);
            }

            return redirect()
                ->route('pre-register.otp.form')
                ->withErrors([
                    'code' => $expired
                        ? 'مهلت استفاده از کد تأیید به پایان رسیده است. لطفاً کد جدید دریافت کنید.'
                        : 'کد تأیید واردشده صحیح نیست.',
                ]);
        }

        // ================= SAVE PRE-REGISTRATION =================
        $registration = $this->persist($pending['data']);

        if (! $registration) {
            $this->otpService->invalidate(
                $phone,
                OtpType::PRE_REGISTER
            );

            $request->session()->forget(self::SESSION_KEY);

            return redirect()
                ->route('pre-register.create')
                ->withErrors([
                    'national_code' => 'این کد ملی قبلاً در سامانه پیش‌ثبت‌نام شده است.',
                ]);
        }

        // ================= CONSUME OTP =================
        // The code must become unusable after successful verification.
        $this->otpService->consume($otp);

        // ================= CLEAR SESSION =================
        $request->session()->forget(self::SESSION_KEY);

        return redirect()
            ->route('pre-register.success')
            ->with(self::SUCCESS_KEY, true);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $pending = $request->session()->get(self::SESSION_KEY);

        if (! $pending) {
            return redirect()
                ->route('pre-register.create')
                ->withErrors(['phone_number' => 'نشست پیش‌ثبت‌نام شما منقضی شده است. لطفاً فرم را دوباره تکمیل کنید.']);
        }

        if ($this->resendAvailableIn($pending) > 0) {
            return redirect()
                ->route('pre-register.otp.form')
                ->withErrors(['code' => 'برای دریافت مجدد کد، لطفاً تا پایان زمان مشخص‌شده صبر کنید.']);
        }

        try {
            $this->otpService->issue(
                $pending['phone'],
                OtpType::PRE_REGISTER,
                [
                    'code' => app()->environment('local') ? '123456' : null,
                    'length' => self::OTP_LENGTH,
                    'ttl_minutes' => self::OTP_TTL_MINUTES,
                ]
            );
        } catch (OtpSendingFailedException $exception) {
            return redirect()
                ->route('pre-register.otp.form')
                ->withErrors(['code' => $exception->getMessage()]);
        }

        $pending['attempts'] = 0;
        $pending['otp_sent_at'] = Carbon::now()->getTimestamp();
        $request->session()->put(self::SESSION_KEY, $pending);

        return redirect()
            ->route('pre-register.otp.form')
            ->with('success', $this->isLocalTestMode()
                ? 'کد آزمایشی ۱۲۳۴۶۵ دوباره فعال شد.'
                : 'کد تأیید ۶ رقمی به شماره همراه شما ارسال شد.');
    }

    public function editPhone(Request $request): RedirectResponse
    {
        $pending = $request->session()->get(self::SESSION_KEY);

        if (! $pending) {
            return redirect()->route('pre-register.create');
        }

        return redirect()
            ->route('pre-register.create')
            ->withInput($pending['data']);
    }

    public function success(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has(self::SUCCESS_KEY)) {
            return redirect()->route('pre-register.create');
        }

        return view('alibnhamze.auth.pre-register', ['step' => 'success']);
    }

    /** @param array<string, mixed> $data */
    protected function persist(array $data): ?PreRegistration
    {
        try {
            return DB::transaction(function () use ($data): ?PreRegistration {
                if (PreRegistration::query()
                    ->where('national_code', $data['national_code'])
                    ->lockForUpdate()
                    ->exists()) {
                    return null;
                }

                $data['phone_verified_at'] = Carbon::now();

                return PreRegistration::create($data);
            });
        } catch (QueryException $exception) {
            // Only treat unique-key violations as duplicates; surface other DB failures.
            if (($exception->errorInfo[1] ?? null) === 1062) {
                return null;
            }

            throw $exception;
        }
    }

    /** @param array<string, mixed> $pending */
    protected function resendAvailableIn(array $pending): int
    {
        $sentAt = (int) ($pending['otp_sent_at'] ?? 0);

        if ($sentAt === 0) {
            return 0;
        }

        return max(0, $sentAt + self::RESEND_COOLDOWN_SECONDS - Carbon::now()->getTimestamp());
    }

    private function invalidatePendingOtp(string $phone): void
    {
        $this->otpService->invalidate(
            $phone,
            OtpType::PRE_REGISTER
        );
    }
}
