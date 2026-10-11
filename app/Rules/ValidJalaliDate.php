<?php

namespace App\Rules;

use App\Support\JalaliDate;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates that the given value is a real Jalaali (Shamsi) date.
 */
class ValidJalaliDate implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && JalaliDate::isValid($value)) {
            return;
        }

        $fail('تاریخ وارد شده معتبر نیست. لطفاً یک تاریخ شمسی صحیح انتخاب کنید.');
    }
}
