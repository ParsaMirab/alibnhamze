<?php

namespace Tests\Feature;

use App\Services\Sms\LogSmsProvider;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class SmsLogPrivacyTest extends TestCase
{
    public function test_log_sms_provider_masks_otp_and_phone_number(): void
    {
        Log::spy();

        $sent = (new LogSmsProvider)->send('09123456789', 'کد تأیید پیش‌ثبت‌نام: 123456');

        $this->assertTrue($sent);

        Log::shouldHaveReceived('info')
            ->once()
            ->with('SMS dispatched', Mockery::on(function (array $context): bool {
                // Neither the OTP nor the full phone number may reach the log.
                return ! str_contains($context['message'], '123456')
                    && ! str_contains($context['phone'], '09123456789')
                    && str_contains($context['message'], '****');
            }));
    }
}
