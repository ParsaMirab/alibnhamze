<?php

namespace App\Support;

/**
 * Normalizes Iranian mobile numbers to the local `09XXXXXXXXX` form the
 * rest of the application already uses (see the login phone regex).
 */
class IranianPhone
{
    /**
     * Normalize a raw phone number: converts Persian digits, removes
     * separators and rewrites international prefixes (+98 / 0098 / 98)
     * to the local `0` prefixed form.
     */
    public static function normalize(mixed $value): string
    {
        $digits = Digits::onlyDigits($value);

        if (str_starts_with($digits, '0098')) {
            $digits = substr($digits, 4);
        } elseif (str_starts_with($digits, '98') && strlen($digits) === 12) {
            $digits = substr($digits, 2);
        }

        // Numbers typed without the leading zero (9123456789).
        if (strlen($digits) === 10 && str_starts_with($digits, '9')) {
            $digits = '0'.$digits;
        }

        return $digits;
    }

    /**
     * Whether the value is a valid Iranian mobile number.
     */
    public static function isValid(mixed $value): bool
    {
        return (bool) preg_match('/^09\d{9}$/', self::normalize($value));
    }
}
