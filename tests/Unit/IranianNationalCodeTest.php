<?php

namespace Tests\Unit;

use App\Rules\IranianNationalCode;
use PHPUnit\Framework\TestCase;

class IranianNationalCodeTest extends TestCase
{
    public function test_valid_national_codes_pass(): void
    {
        // 1234567891 and 0499370899 both satisfy the official checksum.
        $this->assertTrue(IranianNationalCode::isValid('1234567891'));
        $this->assertTrue(IranianNationalCode::isValid('0499370899'));
    }

    public function test_persian_digits_are_accepted(): void
    {
        $this->assertTrue(IranianNationalCode::isValid('۱۲۳۴۵۶۷۸۹۱'));
    }

    public function test_wrong_checksum_is_rejected(): void
    {
        $this->assertFalse(IranianNationalCode::isValid('1234567890'));
    }

    public function test_wrong_length_is_rejected(): void
    {
        $this->assertFalse(IranianNationalCode::isValid('123456789'));
        $this->assertFalse(IranianNationalCode::isValid('12345678901'));
        $this->assertFalse(IranianNationalCode::isValid(''));
    }

    public function test_non_numeric_values_are_rejected(): void
    {
        $this->assertFalse(IranianNationalCode::isValid('abcdefghij'));
    }

    public function test_repeated_digit_codes_are_rejected(): void
    {
        $this->assertFalse(IranianNationalCode::isValid('0000000000'));
        $this->assertFalse(IranianNationalCode::isValid('1111111111'));
    }
}
