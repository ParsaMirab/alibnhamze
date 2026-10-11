<?php

namespace App\Support;

use Carbon\CarbonImmutable;

/**
 * Small, dependency-free helper for Jalaali (Shamsi) <-> Gregorian conversion.
 *
 * The algorithm is the well known Borkowski / jalaali-js conversion which is
 * accurate for the supported Jalaali year range (1178 - 3177).
 */
class JalaliDate
{
    /**
     * The timezone used to resolve "today" for attendance.
     */
    public const TIMEZONE = 'Asia/Tehran';

    /**
     * Practical range accepted for user supplied Jalaali dates.
     */
    public const MIN_YEAR = 1300;

    public const MAX_YEAR = 1500;

    /**
     * Jalaali year breakpoints used by the conversion algorithm.
     *
     * @var array<int, int>
     */
    private const BREAKS = [
        -61, 9, 38, 199, 426, 686, 756, 818, 1111, 1181, 1210,
        1635, 2060, 2097, 2192, 2262, 2324, 2394, 2456, 3178,
    ];

    /**
     * Persian names of the Jalaali months keyed by month number.
     *
     * @var array<int, string>
     */
    public const MONTHS = [
        1 => 'فروردین',
        2 => 'اردیبهشت',
        3 => 'خرداد',
        4 => 'تیر',
        5 => 'مرداد',
        6 => 'شهریور',
        7 => 'مهر',
        8 => 'آبان',
        9 => 'آذر',
        10 => 'دی',
        11 => 'بهمن',
        12 => 'اسفند',
    ];

    /**
     * Persian week day names indexed by the PHP `w` value (0 = Sunday).
     *
     * @var array<int, string>
     */
    public const WEEKDAYS = [
        0 => 'یکشنبه',
        1 => 'دوشنبه',
        2 => 'سه‌شنبه',
        3 => 'چهارشنبه',
        4 => 'پنجشنبه',
        5 => 'جمعه',
        6 => 'شنبه',
    ];

    /**
     * Convert a Gregorian date to its Jalaali representation.
     *
     * @return array{year: int, month: int, day: int}
     */
    public static function fromGregorian(int $gy, int $gm, int $gd): array
    {
        $jdn = self::gregorianToJulianDay($gy, $gm, $gd);

        return self::fromJulianDay($jdn);
    }

    /**
     * Convert a Jalaali date to its Gregorian representation.
     *
     * @return array{year: int, month: int, day: int}
     */
    public static function toGregorian(int $jy, int $jm, int $jd): array
    {
        return self::fromJulianDayToGregorian(self::jalaaliToJulianDay($jy, $jm, $jd));
    }

    /**
     * Parse and validate a Jalaali date string ("1405/07/19" or "1405-07-19").
     *
     * @return array{year: int, month: int, day: int}|null
     */
    public static function parse(?string $value): ?array
    {
        if ($value === null) {
            return null;
        }

        $value = trim(self::toEnglishDigits($value));

        if (! preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $value, $matches)) {
            return null;
        }

        if (! self::isValidDate((int) $matches[1], (int) $matches[2], (int) $matches[3])) {
            return null;
        }

        return [
            'year' => (int) $matches[1],
            'month' => (int) $matches[2],
            'day' => (int) $matches[3],
        ];
    }

    /**
     * Determine whether the given string is a valid Jalaali date.
     */
    public static function isValid(?string $value): bool
    {
        return self::parse($value) !== null;
    }

    /**
     * Convert a Jalaali date string to a Gregorian "Y-m-d" date string.
     */
    public static function toGregorianString(?string $value): ?string
    {
        $parts = self::parse($value);

        if ($parts === null) {
            return null;
        }

        $gregorian = self::toGregorian($parts['year'], $parts['month'], $parts['day']);

        return sprintf('%04d-%02d-%02d', $gregorian['year'], $gregorian['month'], $gregorian['day']);
    }

    /**
     * Convert a Gregorian "Y-m-d" date string to a Jalaali date string.
     */
    public static function fromGregorianString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        try {
            $date = CarbonImmutable::createFromFormat('Y-m-d', substr($value, 0, 10));
        } catch (\Throwable) {
            return null;
        }

        if ($date === false) {
            return null;
        }

        return self::format(self::fromGregorian(
            (int) $date->format('Y'),
            (int) $date->format('n'),
            (int) $date->format('j'),
        ));
    }

    /**
     * Today's Jalaali date in the given timezone.
     *
     * @return array{year: int, month: int, day: int}
     */
    public static function today(?string $timezone = null): array
    {
        $now = CarbonImmutable::now($timezone ?? self::TIMEZONE);

        return self::fromGregorian(
            (int) $now->format('Y'),
            (int) $now->format('n'),
            (int) $now->format('j'),
        );
    }

    /**
     * Format a Jalaali date array as "Y/m/d".
     *
     * @param  array{year: int, month: int, day: int}  $date
     */
    public static function format(array $date): string
    {
        return sprintf('%04d/%02d/%02d', $date['year'], $date['month'], $date['day']);
    }

    /**
     * Number of days in the given Jalaali month.
     */
    public static function monthLength(int $jy, int $jm): int
    {
        if ($jm <= 6) {
            return 31;
        }

        if ($jm <= 11) {
            return 30;
        }

        return self::isLeapYear($jy) ? 30 : 29;
    }

    /**
     * Determine whether the given Jalaali year is a leap year.
     */
    public static function isLeapYear(int $jy): bool
    {
        return self::jalCal($jy)['leap'] === 0;
    }

    /**
     * Persian name of the weekday for the given Jalaali date.
     *
     * @param  array{year: int, month: int, day: int}  $date
     */
    public static function weekdayName(array $date): string
    {
        $gregorian = self::toGregorian($date['year'], $date['month'], $date['day']);

        $dateTime = CarbonImmutable::create(
            $gregorian['year'],
            $gregorian['month'],
            $gregorian['day'],
            0,
            0,
            0,
            self::TIMEZONE,
        );

        return self::WEEKDAYS[$dateTime->dayOfWeek];
    }

    /**
     * The Jalaali weekday (0 = Saturday ... 6 = Friday) for the first day of a month.
     */
    public static function firstWeekdayOfMonth(int $jy, int $jm): int
    {
        $gregorian = self::toGregorian($jy, $jm, 1);

        $timestamp = CarbonImmutable::create(
            $gregorian['year'],
            $gregorian['month'],
            $gregorian['day'],
            0,
            0,
            0,
            self::TIMEZONE,
        );

        // Carbon's dayOfWeek is 0 (Sunday) .. 6 (Saturday); shift so Saturday is 0.
        return ($timestamp->dayOfWeek + 1) % 7;
    }

    /**
     * Validate a Jalaali date using the algorithm's supported range.
     */
    private static function isValidDate(int $jy, int $jm, int $jd): bool
    {
        if ($jy < self::MIN_YEAR || $jy > self::MAX_YEAR) {
            return false;
        }

        if ($jm < 1 || $jm > 12) {
            return false;
        }

        return $jd >= 1 && $jd <= self::monthLength($jy, $jm);
    }

    /**
     * Convert a Jalaali date to a Julian Day Number.
     */
    private static function jalaaliToJulianDay(int $jy, int $jm, int $jd): int
    {
        $calendar = self::jalCal($jy);

        return self::gregorianToJulianDay($calendar['gy'], 3, $calendar['march'])
            + ($jm - 1) * 31
            - self::div($jm, 7) * ($jm - 7)
            + $jd
            - 1;
    }

    /**
     * Convert a Julian Day Number to a Jalaali date.
     *
     * @return array{year: int, month: int, day: int}
     */
    private static function fromJulianDay(int $jdn): array
    {
        $gregorian = self::fromJulianDayToGregorian($jdn);

        $jy = $gregorian['year'] - 621;
        $calendar = self::jalCal($jy);

        $firstDay = self::gregorianToJulianDay($gregorian['year'], 3, $calendar['march']);
        $k = $jdn - $firstDay;

        if ($k >= 0) {
            if ($k <= 185) {
                return [
                    'year' => $jy,
                    'month' => 1 + self::div($k, 31),
                    'day' => self::mod($k, 31) + 1,
                ];
            }

            $k -= 186;
        } else {
            $jy -= 1;
            $k += 179;

            if ($calendar['leap'] === 1) {
                $k += 1;
            }
        }

        return [
            'year' => $jy,
            'month' => 7 + self::div($k, 30),
            'day' => self::mod($k, 30) + 1,
        ];
    }

    /**
     * Determine the Jalaali calendar information for a year.
     *
     * @return array{leap: int, gy: int, march: int}
     */
    private static function jalCal(int $jy): array
    {
        $breaks = self::BREAKS;
        $breakCount = count($breaks);
        $gy = $jy + 621;
        $leapJ = -14;
        $jp = $breaks[0];
        $jump = 0;
        $leap = 0;

        for ($i = 1; $i < $breakCount; $i++) {
            $jm = $breaks[$i];
            $jump = $jm - $jp;

            if ($jy < $jm) {
                break;
            }

            $leapJ += self::div($jump, 33) * 8 + self::div(self::mod($jump, 33), 4);
            $jp = $jm;
        }

        $n = $jy - $jp;

        $leapJ += self::div($n, 33) * 8 + self::div(self::mod($n, 33) + 3, 4);

        if (self::mod($jump, 33) === 4 && $jump - $n === 4) {
            $leapJ += 1;
        }

        $leapG = self::div($gy, 4) - self::div((self::div($gy, 100) + 1) * 3, 4) - 150;

        $march = 20 + $leapJ - $leapG;

        if ($jump - $n < 6) {
            $n = $n - $jump + self::div($jump + 4, 33) * 33;
        }

        $leap = self::mod(self::mod($n + 1, 33) - 1, 4);

        if ($leap === -1) {
            $leap = 4;
        }

        return ['leap' => $leap, 'gy' => $gy, 'march' => $march];
    }

    /**
     * Convert a Gregorian date to a Julian Day Number.
     */
    private static function gregorianToJulianDay(int $gy, int $gm, int $gd): int
    {
        $d = self::div((($gy + self::div($gm - 8, 6) + 100100) * 1461), 4)
            + self::div(153 * self::mod($gm + 9, 12) + 2, 5)
            + $gd
            - 34840408;

        $d = $d - self::div(self::div($gy + 100100 + self::div($gm - 8, 6), 100) * 3, 4) + 752;

        return $d;
    }

    /**
     * Convert a Julian Day Number to a Gregorian date.
     *
     * @return array{year: int, month: int, day: int}
     */
    private static function fromJulianDayToGregorian(int $jdn): array
    {
        $j = 4 * $jdn + 139361631;
        $j = $j + self::div(self::div(4 * $jdn + 183187720, 146097) * 3, 4) * 4 - 3908;

        $i = self::div(self::mod($j, 1461), 4) * 5 + 308;

        $gd = self::div(self::mod($i, 153), 5) + 1;
        $gm = self::mod(self::div($i, 153), 12) + 1;
        $gy = self::div($j, 1461) - 100100 + self::div(8 - $gm, 6);

        return ['year' => $gy, 'month' => $gm, 'day' => $gd];
    }

    /**
     * Integer division truncating toward zero (matches JS `~~(a / b)`).
     */
    private static function div(int $a, int $b): int
    {
        return intdiv($a, $b);
    }

    /**
     * Remainder that keeps the sign of the dividend (matches JS `a % b`).
     */
    private static function mod(int $a, int $b): int
    {
        return $a - intdiv($a, $b) * $b;
    }

    /**
     * Convert Persian/Arabic digits in a string to English digits.
     */
    private static function toEnglishDigits(string $value): string
    {
        return strtr($value, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);
    }
}
