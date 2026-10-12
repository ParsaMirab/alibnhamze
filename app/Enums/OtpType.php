<?php

namespace App\Enums;

enum OtpType: string
{
    case LOGIN = 'login';

    case PRE_REGISTER = 'pre-register';

    /**
     * All supported OTP purposes.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $type): string => $type->value, self::cases());
    }

    /**
     * A short Persian label for the purpose.
     */
    public function label(): string
    {
        return match ($this) {
            self::LOGIN => 'ورود به سامانه',
            self::PRE_REGISTER => 'پیش‌ثبت‌نام',
        };
    }
}
