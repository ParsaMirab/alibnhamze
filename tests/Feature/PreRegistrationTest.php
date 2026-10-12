<?php

namespace Tests\Feature;

use App\Enums\OtpType;
use App\Enums\RankEnum;
use App\Models\Otps;
use App\Models\PreRegistration;
use App\Models\User;
use App\Services\OtpService;
use App\Services\Sms\SmsProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\Support\FakeSmsProvider;
use Tests\TestCase;

class PreRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private FakeSmsProvider $sms;

    private int $counter = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sms = new FakeSmsProvider;
        $this->app->instance(SmsProvider::class, $this->sms);
    }

    public function test_pre_registration_page_loads(): void
    {
        $this->get(route('pre-register.create'))
            ->assertOk()
            ->assertSee('فرم پیش‌ثبت‌نام')
            ->assertSee('پایه تحصیلی')
            ->assertSee('شماره تلفن همراه');
    }

    public function test_valid_payload_initiates_otp_verification(): void
    {
        $payload = $this->payload();

        $this->post(route('pre-register.store'), $payload)
            ->assertRedirect(route('pre-register.otp.form'))
            ->assertSessionHas('success');

        // Nothing is persisted before the phone is verified.
        $this->assertDatabaseCount('pre_registrations', 0);

        $otp = Otps::query()->latest('id')->firstOrFail();
        $this->assertSame(OtpType::PRE_REGISTER->value, $otp->type->value);
        $this->assertSame($payload['phone_number'], $otp->phone);
    }

    public function test_invalid_payload_is_rejected(): void
    {
        $this->post(route('pre-register.store'), [])
            ->assertSessionHasErrors([
                'student_full_name',
                'father_name',
                'mother_full_name',
                'national_code',
                'grade',
                'previous_year_average',
                'previous_year_discipline_score',
                'phone_number',
            ]);

        $this->assertDatabaseCount('pre_registrations', 0);
        $this->assertDatabaseCount('otps', 0);
    }

    public function test_grade_only_accepts_ten_eleven_or_twelve(): void
    {
        foreach ([10, 11, 12] as $grade) {
            $this->post(route('pre-register.store'), $this->payload(['grade' => (string) $grade]))
                ->assertRedirect(route('pre-register.otp.form'));
        }

        $this->post(route('pre-register.store'), $this->payload(['grade' => '13']))
            ->assertSessionHasErrors('grade');

        $this->post(route('pre-register.store'), $this->payload(['grade' => '9']))
            ->assertSessionHasErrors('grade');
    }

    public function test_academic_scores_are_validated(): void
    {
        foreach (['20.5', '-1', '18.755', 'abc'] as $invalid) {
            $this->post(route('pre-register.store'), $this->payload(['previous_year_average' => $invalid]))
                ->assertSessionHasErrors('previous_year_average');
        }

        $this->post(route('pre-register.store'), $this->payload([
            'previous_year_discipline_score' => '25',
        ]))->assertSessionHasErrors('previous_year_discipline_score');

        // Boundary values are accepted.
        $this->post(route('pre-register.store'), $this->payload([
            'previous_year_average' => '0',
            'previous_year_discipline_score' => '20',
        ]))->assertRedirect(route('pre-register.otp.form'));
    }

    public function test_national_code_validation(): void
    {
        $this->post(route('pre-register.store'), $this->payload(['national_code' => '1234567890']))
            ->assertSessionHasErrors('national_code');

        $this->post(route('pre-register.store'), $this->payload(['national_code' => '0000000000']))
            ->assertSessionHasErrors('national_code');

        $this->post(route('pre-register.store'), $this->payload(['national_code' => '12345']))
            ->assertSessionHasErrors('national_code');
    }

    public function test_duplicate_national_codes_are_rejected(): void
    {
        $existing = $this->makeRegistration();

        $this->post(route('pre-register.store'), $this->payload([
            'national_code' => $existing->national_code,
        ]))->assertSessionHasErrors('national_code');

        $this->assertDatabaseCount('pre_registrations', 1);
        $this->assertDatabaseCount('otps', 0);
    }

    public function test_national_code_with_a_leading_zero_is_preserved(): void
    {
        $payload = $this->payload(['national_code' => '0499370899']);

        $this->post(route('pre-register.store'), $payload)
            ->assertRedirect(route('pre-register.otp.form'));

        $code = Otps::query()->latest('id')->firstOrFail()->code;

        $this->post(route('pre-register.otp.verify'), ['code' => $code])
            ->assertRedirect(route('pre-register.success'));

        $this->assertSame('0499370899', PreRegistration::query()->firstOrFail()->national_code);
    }

    public function test_refreshing_the_otp_page_does_not_extend_the_expiry(): void
    {
        $this->startFlow();

        $before = Otps::query()->latest('id')->firstOrFail()->expires_at;

        $this->get(route('pre-register.otp.form'))->assertOk();
        $this->get(route('pre-register.otp.form'))->assertOk();

        $this->assertEquals($before, Otps::query()->latest('id')->firstOrFail()->expires_at);
    }

    public function test_success_state_is_guarded_without_a_completed_registration(): void
    {
        $this->get(route('pre-register.success'))
            ->assertRedirect(route('pre-register.create'));
    }

    public function test_otp_is_six_digits_and_scoped_to_pre_registration(): void
    {
        [, $code] = $this->startFlow();

        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);

        $otp = Otps::query()->latest('id')->firstOrFail();
        $this->assertSame(OtpType::PRE_REGISTER->value, $otp->type->value);
        $this->assertTrue($this->sms->lastCode() === $code);
    }

    public function test_correct_otp_saves_the_registration_with_all_fields(): void
    {
        [$payload, $code] = $this->startFlow();

        $this->post(route('pre-register.otp.verify'), ['code' => $code])
            ->assertRedirect(route('pre-register.success'));

        $registration = PreRegistration::query()->firstOrFail();

        $this->assertSame($payload['student_full_name'], $registration->student_full_name);
        $this->assertSame($payload['father_name'], $registration->father_name);
        $this->assertSame($payload['mother_full_name'], $registration->mother_full_name);
        $this->assertSame($payload['national_code'], $registration->national_code);
        $this->assertSame(10, $registration->grade);
        $this->assertSame('18.75', $registration->previous_year_average);
        $this->assertSame('19.50', $registration->previous_year_discipline_score);
        $this->assertSame($payload['phone_number'], $registration->phone_number);
        $this->assertNotNull($registration->phone_verified_at);

        $this->get(route('pre-register.success'))
            ->assertOk()
            ->assertSee('با موفقیت ثبت شد');
    }

    public function test_incorrect_otp_does_not_save_the_registration(): void
    {
        [$payload, $code] = $this->startFlow();

        $wrong = $code === '111111' ? '222222' : '111111';

        $this->post(route('pre-register.otp.verify'), ['code' => $wrong])
            ->assertSessionHasErrors('code');

        $this->assertDatabaseCount('pre_registrations', 0);
        // The pending flow is still available for another attempt.
        $this->assertTrue(session()->has('pre_registration.pending'));
    }

    public function test_expired_otp_does_not_save_the_registration(): void
    {
        [, $code] = $this->startFlow();

        Otps::query()->update(['expires_at' => Carbon::now()->subMinute()]);

        $this->post(route('pre-register.otp.verify'), ['code' => $code])
            ->assertSessionHasErrors([
                'code' => 'مهلت استفاده از کد تأیید به پایان رسیده است. لطفاً کد جدید دریافت کنید.',
            ]);

        $this->assertDatabaseCount('pre_registrations', 0);
    }

    public function test_otp_cannot_be_reused(): void
    {
        [$payload, $code] = $this->startFlow();

        $this->post(route('pre-register.otp.verify'), ['code' => $code])
            ->assertRedirect(route('pre-register.success'));

        $this->assertNotNull(Otps::query()->latest('id')->firstOrFail()->used_at);

        // Re-arm the exact same pending flow and try the consumed code again.
        $this->withSession([
            'pre_registration.pending' => [
                'data' => $payload,
                'phone' => $payload['phone_number'],
                'attempts' => 0,
                'otp_sent_at' => Carbon::now()->getTimestamp(),
            ],
        ]);

        $this->post(route('pre-register.otp.verify'), ['code' => $code])
            ->assertSessionHasErrors('code');

        $this->assertDatabaseCount('pre_registrations', 1);
    }

    public function test_repeated_submissions_do_not_create_duplicates(): void
    {
        [$payload, $code] = $this->startFlow();

        $this->post(route('pre-register.otp.verify'), ['code' => $code])->assertRedirect();

        // Submitting the same national code again is rejected.
        $this->post(route('pre-register.store'), $payload)
            ->assertSessionHasErrors('national_code');

        $this->assertDatabaseCount('pre_registrations', 1);
    }

    public function test_verification_requires_a_pending_registration(): void
    {
        $this->get(route('pre-register.otp.form'))
            ->assertRedirect(route('pre-register.create'));

        $this->post(route('pre-register.otp.verify'), ['code' => '123456'])
            ->assertRedirect(route('pre-register.create'))
            ->assertSessionHasErrors('phone_number');

        $this->assertDatabaseCount('pre_registrations', 0);
    }

    public function test_edit_phone_returns_to_the_form_with_values_preserved(): void
    {
        $payload = $this->payload();

        $this->post(route('pre-register.store'), $payload)
            ->assertRedirect(route('pre-register.otp.form'));

        $this->post(route('pre-register.edit-phone'))
            ->assertRedirect(route('pre-register.create'))
            ->assertSessionHasInput('phone_number', $payload['phone_number'])
            ->assertSessionHasInput('student_full_name', $payload['student_full_name']);
    }

    public function test_user_can_change_phone_number_after_requesting_otp(): void
    {
        [$payload] = $this->startFlow();

        $newPhone = '09359999999';

        $this->post(route('pre-register.store'), array_merge($payload, ['phone_number' => $newPhone]))
            ->assertRedirect(route('pre-register.otp.form'));

        // The newest code is active for the new phone...
        $this->assertNotNull(
            Otps::query()->where('phone', $newPhone)->whereNull('used_at')->first()
        );

        // ...and the code issued for the old phone is invalidated.
        $this->assertNull(
            Otps::query()->where('phone', $payload['phone_number'])->whereNull('used_at')->first()
        );
    }

    public function test_resend_cooldown_is_enforced(): void
    {
        [$payload] = $this->startFlow();

        $this->post(route('pre-register.otp.resend'))
            ->assertRedirect(route('pre-register.otp.form'))
            ->assertSessionHasErrors('code');

        $this->assertSame(1, Otps::query()->count());

        $this->travel(121)->seconds();

        $this->post(route('pre-register.otp.resend'))
            ->assertRedirect(route('pre-register.otp.form'))
            ->assertSessionHas('success');

        $this->assertSame(2, Otps::query()->count());
        $this->assertSame(
            1,
            Otps::query()->where('phone', $payload['phone_number'])->whereNull('used_at')->count()
        );
    }

    public function test_incorrect_attempts_are_limited(): void
    {
        $this->startFlow();

        $wrong = '000000';

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('pre-register.otp.verify'), ['code' => $wrong])
                ->assertSessionHasErrors('code');
        }

        $this->post(route('pre-register.otp.verify'), ['code' => $wrong])
            ->assertRedirect(route('pre-register.create'))
            ->assertSessionHasErrors('code');

        $this->assertDatabaseCount('pre_registrations', 0);
        $this->assertFalse(session()->has('pre_registration.pending'));
    }

    public function test_sms_failure_is_handled_and_the_code_is_not_kept(): void
    {
        $this->sms->shouldFail = true;

        $this->post(route('pre-register.store'), $this->payload())
            ->assertSessionHasErrors('phone_number');

        $this->assertDatabaseCount('pre_registrations', 0);
        $this->assertDatabaseCount('otps', 0);
    }

    public function test_pending_data_is_cleared_after_successful_verification(): void
    {
        [, $code] = $this->startFlow();

        $this->post(route('pre-register.otp.verify'), ['code' => $code])
            ->assertRedirect(route('pre-register.success'));

        $this->assertFalse(session()->has('pre_registration.pending'));
    }

    public function test_a_login_otp_cannot_verify_a_pre_registration(): void
    {
        $phone = '09120000001';
        $user = $this->makeUser($phone);

        // A *valid* login OTP exists for this phone.
        app(OtpService::class)->store(
            $phone,
            OtpType::LOGIN,
            '123456',
            Carbon::now()->addMinutes(10),
            $user->id,
        );

        $this->startFlow(['phone_number' => $phone]);

        // Force the pre-registration code to a different value for determinism.
        Otps::query()->where('type', OtpType::PRE_REGISTER->value)->update(['code' => '654321']);

        $this->post(route('pre-register.otp.verify'), ['code' => '123456'])
            ->assertSessionHasErrors('code');

        $this->assertDatabaseCount('pre_registrations', 0);
    }

    public function test_a_pre_registration_otp_cannot_log_a_user_in(): void
    {
        $phone = '09120000002';
        $user = $this->makeUser($phone);

        [, $code] = $this->startFlow(['phone_number' => $phone]);

        $this->post(route('login.verifyOtp'), ['phone' => $phone, 'code' => $code])
            ->assertSessionHasErrors('code');

        $this->assertGuest();
    }

    /**
     * Build a valid pre-registration payload.
     *
     * @return array<string, string>
     */
    private function payload(array $overrides = []): array
    {
        $this->counter++;
        $n = $this->counter;

        return array_merge([
            'student_full_name' => "دانش‌آموز {$n}",
            'father_name' => 'محمد',
            'mother_full_name' => 'زهرا احمدی',
            'national_code' => $this->nationalCodeFor($n),
            'grade' => '10',
            'previous_year_average' => '18.75',
            'previous_year_discipline_score' => '19.50',
            'phone_number' => '0912'.str_pad((string) $n, 7, '0', STR_PAD_LEFT),
        ], $overrides);
    }

    /**
     * Submit the form and return [payload, otp code].
     *
     * @return array{0: array<string, string>, 1: string}
     */
    private function startFlow(array $overrides = []): array
    {
        $payload = $this->payload($overrides);

        $this->post(route('pre-register.store'), $payload)
            ->assertRedirect(route('pre-register.otp.form'));

        $otp = Otps::query()
            ->where('type', OtpType::PRE_REGISTER->value)
            ->latest('id')
            ->firstOrFail();

        return [$payload, $otp->code];
    }

    private function makeRegistration(array $overrides = []): PreRegistration
    {
        $this->counter++;
        $n = $this->counter;

        return PreRegistration::create(array_merge([
            'student_full_name' => "ثبت‌شده {$n}",
            'father_name' => 'پدر',
            'mother_full_name' => 'مادر',
            'national_code' => $this->nationalCodeFor($n),
            'grade' => 10,
            'previous_year_average' => 15.5,
            'previous_year_discipline_score' => 18,
            'phone_number' => '0913'.str_pad((string) $n, 7, '0', STR_PAD_LEFT),
            'phone_verified_at' => Carbon::now(),
        ], $overrides));
    }

    private function makeUser(string $phone): User
    {
        return User::create([
            'first_name' => 'کاربر',
            'last_name' => 'نمونه',
            'national_code' => '3'.substr($phone, -9),
            'phone' => $phone,
            'is_admin' => false,
            'rank' => RankEnum::STUDENT->value,
            'grade' => 10,
        ]);
    }

    /**
     * Produce a national code with a valid checksum from a numeric seed.
     */
    private function nationalCodeFor(int $seed): string
    {
        $base = str_pad((string) (100000000 + $seed), 9, '0', STR_PAD_LEFT);

        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $base[$i]) * (10 - $i);
        }

        $remainder = $sum % 11;
        $check = $remainder < 2 ? $remainder : 11 - $remainder;

        return $base.$check;
    }
}
