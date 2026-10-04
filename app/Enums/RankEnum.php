<?php

namespace App\Enums;

enum RankEnum: string
{
    case MANAGER = 'manager';
    case STUDENT = 'student';
    case DEPUTY = 'deputy';

    public function label(): string
    {
        return match ($this) {
            self::MANAGER => 'مدیر',
            self::STUDENT => 'دانش‌آموز',
            self::DEPUTY => 'معاون',
        };
    }
}
