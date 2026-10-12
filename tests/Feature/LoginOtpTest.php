<?php

namespace Tests\Feature;

use App\Enums\OtpType;
use App\Enums\RankEnum;
use App\Models\Otps;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LoginOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads(): void
    {
        $this->get(route('login.showLoginForm'))
            ->assertOk()
            ->assertSee('ورود به سامانه');
    }

    public function test_login_otp_flow_still_works(): void
    {
        $user = $this->makeUser('09121111111');

        $this->post(route('login.sendOtp'), ['phone' => $user->phone])
            ->assertRedirect(route('login.showOtpForm', ['phone' => $user->phone]));

        $otp = Otps::query()->where('phone', $user->phone)->latest('id')->firstOrFail();

        $this->assertSame(OtpType::LOGIN->value, $otp->type->value);
        $this->assertSame('123456', $otp->code);
        $this->assertSame(6, strlen($otp->code));

        $this->post(route('login.verifyOtp'), ['phone' => $user->phone, 'code' => '123456'])
            ->assertRedirect(route('student.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($otp->fresh()->used_at);
    }

    public function test_unknown_phone_is_rejected(): void
    {
        $this->post(route('login.sendOtp'), ['phone' => '09129999999'])
            ->assertSessionHasErrors('phone');

        $this->assertDatabaseCount('otps', 0);
    }

    public function test_invalid_login_otp_is_rejected(): void
    {
        $user = $this->makeUser('09123333333');

        $this->post(route('login.sendOtp'), ['phone' => $user->phone]);

        $this->post(route('login.verifyOtp'), ['phone' => $user->phone, 'code' => '000000'])
            ->assertSessionHasErrors('code');

        $this->assertGuest();
    }

    public function test_sending_a_login_otp_does_not_touch_pre_registration_codes(): void
    {
        $user = $this->makeUser('09122222222');

        $preOtp = app(OtpService::class)->store(
            $user->phone,
            OtpType::PRE_REGISTER,
            '654321',
            Carbon::now()->addMinutes(2),
        );

        $this->post(route('login.sendOtp'), ['phone' => $user->phone])->assertRedirect();

        // The pre-registration code must survive untouched.
        $this->assertNull($preOtp->fresh()->used_at);
        $this->assertNotNull(
            app(OtpService::class)->find($user->phone, OtpType::PRE_REGISTER, '654321')
        );
    }

    private function makeUser(string $phone): User
    {
        return User::create([
            'first_name' => 'دانش‌آموز',
            'last_name' => 'نمونه',
            'national_code' => '2'.substr($phone, -9),
            'phone' => $phone,
            'is_admin' => false,
            'rank' => RankEnum::STUDENT->value,
            'grade' => 10,
        ]);
    }
}
