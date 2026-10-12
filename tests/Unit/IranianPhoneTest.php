<?php

namespace Tests\Unit;

use App\Support\IranianPhone;
use PHPUnit\Framework\TestCase;

class IranianPhoneTest extends TestCase
{
    public function test_local_numbers_are_valid(): void
    {
        $this->assertTrue(IranianPhone::isValid('09123456789'));
        $this->assertTrue(IranianPhone::isValid('09351234567'));
    }

    public function test_persian_digits_are_normalized(): void
    {
        $this->assertSame('09123456789', IranianPhone::normalize('۰۹۱۲۳۴۵۶۷۸۹'));
        $this->assertTrue(IranianPhone::isValid('۰۹۱۲۳۴۵۶۷۸۹'));
    }

    public function test_separators_are_stripped(): void
    {
        $this->assertSame('09123456789', IranianPhone::normalize('0912 345 6789'));
        $this->assertSame('09123456789', IranianPhone::normalize('0912-345-6789'));
    }

    public function test_international_prefixes_are_normalized(): void
    {
        $this->assertSame('09123456789', IranianPhone::normalize('+989123456789'));
        $this->assertSame('09123456789', IranianPhone::normalize('00989123456789'));
        $this->assertSame('09123456789', IranianPhone::normalize('989123456789'));
    }

    public function test_number_without_leading_zero_is_normalized(): void
    {
        $this->assertSame('09123456789', IranianPhone::normalize('9123456789'));
    }

    public function test_invalid_numbers_are_rejected(): void
    {
        $this->assertFalse(IranianPhone::isValid('0812345678'));
        $this->assertFalse(IranianPhone::isValid('0912345678'));
        $this->assertFalse(IranianPhone::isValid('091234567890'));
        $this->assertFalse(IranianPhone::isValid(''));
    }
}
