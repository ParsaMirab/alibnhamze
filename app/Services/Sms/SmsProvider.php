<?php

namespace App\Services\Sms;

/**
 * A minimal seam over the school's SMS provider so the application never
 * depends on a concrete vendor and tests can fake delivery.
 */
interface SmsProvider
{
    /**
     * Attempt to deliver an SMS.
     *
     * Implementations must return `false` (or throw) whenever the provider
     * did not accept the message, so callers never treat a failure as sent.
     */
    public function send(string $phone, string $message): bool;
}
