<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when the SMS gateway rejects an OTP message so the caller can
 * report a delivery failure instead of a false success.
 */
class OtpSendingFailedException extends RuntimeException
{
    /**
     * The exception message shown to the user (Persian).
     *
     * @var string
     */
    protected $message = 'ارسال پیامک تأیید با خطا مواجه شد. لطفاً چند لحظه بعد دوباره تلاش کنید.';
}
