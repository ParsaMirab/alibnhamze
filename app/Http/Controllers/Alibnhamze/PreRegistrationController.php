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

/**
 * Public student pre-registration flow.
 *
 * The application is only persisted after the phone number is verified with a
 * six-digit OTP. Until then the validated form data lives in the session.
 */
class PreRegistrationController extends Controller
{
    /**
     * Session key holding the pending (unverified) application.
     */
    private const SESSION_KEY = 'pre_registration.pending';

    /**
     * Session key set once right after a successful registration.
     */
    private const SUCCESS_KEY = 'pre_registration_success';

    private const OTP_LENGTH = 6;

    private const OTP_TTL_MINUTES = 2;

    private const RESEND_COOLDOWN_SECONDS = 120;

    private const MAX_ATTEMPTS = 5;

    public function __construct(private readonly OtpService $otpService) {}

    /**
     * Display the pre-registration form.
     */
    public function create(): View
    {
        return view('alibnhamze.auth.pre-register', [
            'step' => 'form',
        ]);
    }

    /**
     * Validate the form, stash it in the session and send the first OTP.
     */
    public function store(StorePreRegistrationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (PreRegistration::query()->where('national_code', $data['national_code'])->exists()) {
            return back()
                ->withErrors(['national_code' => 'این کد ملی قبلاً در سامانه پیش‌ثبت‌نام شده است.'])
                ->withInput();
        }

        // When the applicant corrects the phone number, the code issued to the
        // previous number must stop working immediately.
        $previous = $request->session()->get(self::SESSION_KEY);

        if ($previous && ($previous['phone'] ?? null) !== $data['phone_number']) {
            $this->otpService->invalidate($previous['phone'], OtpType::PRE_REGISTER);
        }

        $request->session()->put(self::SESSION_KEY, [
            'data' => $data,
            'phone' => $data['phone_number'],
            'attempts' => 0,
            'otp_sent_at' => Carbon::now()->getTimestamp(),
        ]);

        try {
            $this->otpService->issue($data['phone_number'], OtpType::PRE_REGISTER, [
                'length' => self::OTP_LENGTH,
                'ttl_minutes' => self::OTP_TTL_MINUTES,
            ]);
        } catch (OtpSendingFailedException $exception) {
            return back()
                ->withErrors(['phone_number' => $exception->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('pre-register.otp.form')
            ->with('success', 'کد تأیید ۶ رقمی به شماره همراه شما ارسال شد.');
    }

    /**
     * Display the OTP verification step.
     */
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

    /**
     * Verify the submitted OTP and persist the application exactly once.
     */
    public function verifyOtp(VerifyPreRegistrationOtpRequest $request): RedirectResponse
    {
        $pending = $request->session()->get(self::SESSION_KEY);

        if (! $pending) {
            return redirect()
                ->route('pre-register.create')
                ->withErrors(['phone_number' => 'نشست پیش‌ثبت‌نام شما منقضی شده است. لطفاً فرم را دوباره تکمیل کنید.']);
        }

        $phone = $pending['phone'];
        $code = $request->validated()['code'];

        // Too many wrong guesses: burn the code and force a fresh start.
        if ((int) ($pending['attempts'] ?? 0) >= self::MAX_ATTEMPTS) {
            $this->otpService->invalidate($phone, OtpType::PRE_REGISTER);
            $request->session()->forget(self::SESSION_KEY);

            return redirect()
                ->route('pre-register.create')
                ->withErrors(['code' => 'تعداد تلاش‌های ناموفق بیش از حد مجاز شد. لطفاً دوباره کد دریافت کنید.']);
        }

        $otp = $this->otpService->find($phone, OtpType::PRE_REGISTER, $code);

        if (! $otp) {
            $pending['attempts'] = (int) ($pending['attempts'] ?? 0) + 1;
            $request->session()->put(self::SESSION_KEY, $pending);

            $latest = $this->otpService->latest($phone, OtpType::PRE_REGISTER);
            $expired = $latest && ! $latest->isConsumed() && $latest->isExpired();

            return redirect()
                ->route('pre-register.otp.form')
                ->withErrors([
                    'code' => $expired
                        ? 'مهلت استفاده از کد تأیید به پایان رسیده است. لطفاً کد جدید دریافت کنید.'
                        : 'کد تأیید واردشده صحیح نیست.',
                ]);
        }

        $registration = $this->persist($pending['data']);

        if (! $registration) {
            $request->session()->forget(self::SESSION_KEY);

            return redirect()
                ->route('pre-register.create')
                ->withErrors(['national_code' => 'این کد ملی قبلاً در سامانه پیش‌ثبت‌نام شده است.']);
        }

        // Consume the code so it can never be replayed, then clear the stash.
        $this->otpService->consume($otp);
        $request->session()->forget(self::SESSION_KEY);

        return redirect()
            ->route('pre-register.success')
            ->with(self::SUCCESS_KEY, true);
    }

    /**
     * Resend a fresh code once the cooldown has passed.
     */
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
            $this->otpService->issue($pending['phone'], OtpType::PRE_REGISTER, [
                'length' => self::OTP_LENGTH,
                'ttl_minutes' => self::OTP_TTL_MINUTES,
            ]);
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
            ->with('success', 'کد تأیید ۶ رقمی به شماره همراه شما ارسال شد.');
    }

    /**
     * Return to the form with the pending values preserved so the phone
     * number can be corrected.
     */
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

    /**
     * Display the success state after a verified registration.
     */
    public function success(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has(self::SUCCESS_KEY)) {
            return redirect()->route('pre-register.create');
        }

        return view('alibnhamze.auth.pre-register', [
            'step' => 'success',
        ]);
    }

    /**
     * Persist the verified application inside a transaction, guarding the
     * national code uniqueness against concurrent submissions.
     *
     * @param  array<string, mixed>  $data
     */
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
        } catch (QueryException) {
            // The unique index rejected a concurrent duplicate.
            return null;
        }
    }

    /**
     * Seconds left before a new code may be requested.
     *
     * @param  array<string, mixed>  $pending
     */
    protected function resendAvailableIn(array $pending): int
    {
        $sentAt = (int) ($pending['otp_sent_at'] ?? 0);

        if ($sentAt === 0) {
            return 0;
        }

        $availableAt = $sentAt + self::RESEND_COOLDOWN_SECONDS;

        return max(0, $availableAt - Carbon::now()->getTimestamp());
    }
}
