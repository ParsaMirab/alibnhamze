<?php

namespace App\Rules;

use App\Support\Digits;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an Iranian national code (کد ملی) including its checksum digit.
 */
class IranianNationalCode implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::isValid($value)) {
            $fail('کد ملی واردشده معتبر نیست.');
        }
    }

    /**
     * Validate a raw national code value.
     */
    public static function isValid(mixed $value): bool
    {
        $code = Digits::toEnglish($value);

        if (! preg_match('/^\d{10}$/', $code)) {
            return false;
        }

        // Codes built from a single repeated digit pass the checksum but are invalid.
        if (preg_match('/^(\d)\1{9}$/', $code)) {
            return false;
        }

        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $code[$i]) * (10 - $i);
        }

        $remainder = $sum % 11;
        $expected = $remainder < 2 ? $remainder : 11 - $remainder;

        return $expected === (int) $code[9];
    }
}
