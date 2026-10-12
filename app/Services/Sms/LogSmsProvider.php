<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

/**
 * Default provider used when no real SMS gateway is configured.
 *
 * It only writes a masked record to the log: OTP values (and any other
 * numeric secret) are never persisted in plain text.
 */
class LogSmsProvider implements SmsProvider
{
    /**
     * {@inheritDoc}
     */
    public function send(string $phone, string $message): bool
    {
        Log::info('SMS dispatched', [
            'phone' => $this->mask($phone),
            'message' => $this->mask($message),
        ]);

        return true;
    }

    /**
     * Hide any run of four or more digits (OTP codes, phone numbers).
     */
    private function mask(string $value): string
    {
        return preg_replace('/\d{4,}/', '****', $value) ?? $value;
    }
}
