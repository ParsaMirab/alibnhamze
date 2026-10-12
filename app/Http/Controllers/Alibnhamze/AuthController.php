<?php

namespace App\Http\Controllers\Alibnhamze;

use App\Enums\OtpType;
use App\Enums\RankEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    public function showLoginForm()
    {
        return view('alibnhamze.auth.login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09\d{9}$/'],
        ]);

        $phone = $request->input('phone');

        $user = User::where('phone', $phone)->first();

        if (! $user) {
            return back()
                ->withErrors([
                    'phone' => 'این شماره موبایل در سامانه ثبت نشده است.',
                ])
                ->withInput([
                    'phone' => $phone,
                ]);
        }

        $otpCode = '123456';

        // Only login codes are invalidated: pre-registration OTPs for the same
        // phone number must survive untouched.
        $this->otpService->invalidate($phone, OtpType::LOGIN);

        $this->otpService->store(
            $phone,
            OtpType::LOGIN,
            $otpCode,
            Carbon::now()->addMinutes(10),
            $user->id,
        );

        // Beta Mode
        session([
            'otp_code' => $otpCode,
        ]);

        return redirect()
            ->route('login.showOtpForm', ['phone' => $phone])
            ->with('success', 'کد تأیید ارسال شد');
    }

    public function showOtpForm(string $phone)
    {
        return view('alibnhamze.auth.login', [
            'phone' => $phone,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09\d{9}$/'],
            'code' => ['required', 'digits:6'],
        ]);

        $phone = $request->input('phone');
        $code = $request->input('code');

        // پیدا کردن OTP معتبر — تنها کدهای ورود مجاز هستند
        $otp = $this->otpService->find($phone, OtpType::LOGIN, $code);

        if (! $otp) {
            return back()
                ->withErrors([
                    'code' => 'کد تأیید نامعتبر یا منقضی شده است.',
                ])
                ->withInput([
                    'phone' => $phone,
                ]);
        }

        // پیدا کردن کاربر ثبت‌شده
        $user = User::where('phone', $phone)->first();

        // اگر User بین ارسال OTP و Verify حذف شده باشد
        if (! $user) {
            return back()
                ->withErrors([
                    'code' => 'کاربر مربوط به این شماره در سامانه یافت نشد.',
                ])
                ->withInput([
                    'phone' => $phone,
                ]);
        }

        // OTP مصرف شده
        $otp->forceFill(['user_id' => $user->id])->save();
        $this->otpService->consume($otp);

        // Login اصلی
        Auth::login($user);

        // اگر کاربر Admin است، Guard مربوط به Admin نیز Login شود
        if ($user->is_admin) {
            Auth::guard('admin')->login($user);
        }

        // حذف OTP از Session بعد از ورود موفق
        session()->forget('otp_code');

        // انتقال بر اساس Rank
        return $this->redirectAfterLogin($user);
    }

    protected function redirectAfterLogin($user)
    {
        return redirect()->intended(
            $this->getRedirectPath($user)
        );
    }

    protected function getRedirectPath($user)
    {
        return match ($user->rank) {
            RankEnum::MANAGER->value,
            RankEnum::DEPUTY->value,
            RankEnum::TEACHER->value => route('admin.dashboard'),

            RankEnum::STUDENT->value => route('student.dashboard'),

            RankEnum::PARENT->value => route('parent.dashboard'),

            default => route('home'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.showLoginForm');
    }
}
