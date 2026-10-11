<?php

namespace Database\Seeders;

use App\Enums\RankEnum;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClassManagementSeeder extends Seeder
{
    public function run(): void
    {
        // ================= ADMIN USERS =================

        $admins = [
            ['1111111111', 'مدیر', 'سیستم', '09111111111', RankEnum::MANAGER],
            ['2222222222', 'معاون', 'مجاز', '09222222222', RankEnum::DEPUTY],
            ['3333333333', 'معاون', 'دوم', '09333333333', RankEnum::DEPUTY],
        ];

        foreach ($admins as [$nationalCode, $firstName, $lastName, $phone, $rank]) {
            User::updateOrCreate(
                ['national_code' => $nationalCode],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $phone,
                    'is_admin' => true,
                    'password' => Hash::make('password'),
                    'rank' => $rank->value,
                    'grade' => null,
                ]
            );
        }

        // ================= TEACHERS =================

        $teacherData = [
            ['علی', 'محمدی', 'ریاضی'],
            ['رضا', 'احمدی', 'فیزیک'],
            ['حسین', 'کریمی', 'برنامه‌نویسی'],
            ['مهدی', 'رضایی', 'شبکه'],
            ['محمد', 'حسینی', 'برق صنعتی'],
            ['امیر', 'موسوی', 'الکترونیک'],
            ['سعید', 'اکبری', 'ادبیات فارسی'],
            ['جواد', 'مرادی', 'زبان انگلیسی'],
        ];

        $teachers = [];

        foreach ($teacherData as $index => [$firstName, $lastName, $subject]) {
            $number = $index + 1;

            $teacher = User::updateOrCreate(
                [
                    'national_code' => '444' . str_pad($number, 7, '0', STR_PAD_LEFT),
                ],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => '0912' . str_pad($number, 7, '0', STR_PAD_LEFT),
                    'is_admin' => true,
                    'password' => Hash::make('password'),
                    'rank' => RankEnum::TEACHER->value,
                    'grade' => null,
                ]
            );

            $teachers[] = [
                'model' => $teacher,
                'subject' => $subject,
            ];
        }

        // ================= CLASSES =================

        $classData = [
            ['دهم کامپیوتر ۱', 10],
            ['دهم برق ۱', 10],
            ['یازدهم کامپیوتر ۱', 11],
            ['یازدهم برق ۱', 11],
            ['دوازدهم کامپیوتر ۱', 12],
            ['دوازدهم برق ۱', 12],
        ];

        $classes = [];

        foreach ($classData as [$title, $grade]) {
            $classes[] = SchoolClass::updateOrCreate(
                ['title' => $title],
                ['grade' => $grade]
            );
        }

        // ================= CLASS TEACHERS =================

        foreach ($classes as $classIndex => $class) {
            // Assign four teachers to each class.
            for ($i = 0; $i < 4; $i++) {
                $teacherIndex = ($classIndex + $i) % count($teachers);
                $teacher = $teachers[$teacherIndex];

                DB::table('class_teachers')->insertOrIgnore([
                    'class_id' => $class->id,
                    'teacher_id' => $teacher['model']->id,
                    'subject' => $teacher['subject'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ================= STUDENTS =================

        $firstNames = [
            'محمد', 'علی', 'حسین', 'رضا', 'امیر',
            'مهدی', 'سینا', 'پارسا', 'آرین', 'کیان',
            'سام', 'یاسین', 'طاها', 'ماهان', 'محمدرضا',
            'ابوالفضل', 'عرفان', 'نیما', 'آرش', 'پویا',
        ];

        $lastNames = [
            'محمدی', 'احمدی', 'کریمی', 'رضایی', 'حسینی',
            'موسوی', 'اکبری', 'مرادی', 'جعفری', 'قاسمی',
            'نوری', 'رحیمی', 'صادقی', 'امیری', 'زارعی',
            'شریفی', 'کاظمی', 'عباسی', 'یوسفی', 'حیدری',
        ];

        $studentsPerClass = 10;

        foreach ($classes as $classIndex => $class) {
            for ($i = 1; $i <= $studentsPerClass; $i++) {
                $number = ($classIndex * $studentsPerClass) + $i;

                $firstName = $firstNames[($number - 1) % count($firstNames)];
                $lastName = $lastNames[
                ($number + intdiv($number, 3)) % count($lastNames)
                ];

                $student = User::updateOrCreate(
                    [
                        'national_code' => '666' . str_pad($number, 7, '0', STR_PAD_LEFT),
                    ],
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone' => '0990' . str_pad($number, 7, '0', STR_PAD_LEFT),
                        'is_admin' => false,
                        'password' => Hash::make('password'),
                        'rank' => RankEnum::STUDENT->value,
                        'grade' => $class->grade,
                    ]
                );

                DB::table('class_students')->insertOrIgnore([
                    'class_id' => $class->id,
                    'student_id' => $student->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Class Management Seeder completed.');
        $this->command->info('8 teachers, 6 classes and 60 students seeded.');
    }
}
