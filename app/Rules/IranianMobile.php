<?php

namespace App\Rules;

use App\Support\IranianPhone;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an Iranian mobile number, accepting Persian digits and the
 * common international prefixes which are normalized first.
 */
class IranianMobile implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! IranianPhone::isValid($value)) {
            $fail('شماره تلفن همراه واردشده معتبر نیست. نمونه صحیح: 09123456789');
        }
    }
}
