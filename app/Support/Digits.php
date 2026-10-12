<?php

namespace App\Support;

/**
 * Normalizes the Persian (۰-۹) and Arabic (٠-٩) digits that users
 * frequently type into forms into plain ASCII digits.
 */
class Digits
{
    /**
     * Convert Persian / Arabic digits to their ASCII equivalents.
     */
    public static function toEnglish(mixed $value): string
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return '';
        }

        return strtr((string) $value, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);
    }

    /**
     * Convert to ASCII digits and strip everything that is not a digit.
     */
    public static function onlyDigits(mixed $value): string
    {
        return preg_replace('/\D+/', '', self::toEnglish($value)) ?? '';
    }
}
