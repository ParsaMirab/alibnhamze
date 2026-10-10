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
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users with different ranks
        User::updateOrCreate(
            [
                'national_code' => '1111111111',
            ],
            [
                'first_name' => 'مدیر',
                'last_name' => 'سیستم',
                'phone' => '09111111111',
                'is_admin' => true,
                'password' => Hash::make('password'),
                'rank' => RankEnum::MANAGER->value,
                'grade' => null,
            ]
        );

        User::updateOrCreate(
            [
                'national_code' => '2222222222',
            ],
            [
                'first_name' => 'معاون',
                'last_name' => 'مجاز',
                'phone' => '09222222222',
                'is_admin' => true,
                'password' => Hash::make('password'),
                'rank' => RankEnum::DEPUTY->value,
                'grade' => null,
            ]
        );

        User::updateOrCreate(
            [
                'national_code' => '3333333333',
            ],
            [
                'first_name' => 'معاون',
                'last_name' => 'دوم',
                'phone' => '09333333333',
                'is_admin' => true,
                'password' => Hash::make('password'),
                'rank' => RankEnum::DEPUTY->value,
                'grade' => null,
            ]
        );

        // Create teacher users
        $teacher1 = User::updateOrCreate(
            [
                'national_code' => '4444444444',
            ],
            [
                'first_name' => 'علی',
                'last_name' => 'محمدی',
                'phone' => '09444444444',
                'is_admin' => true,
                'password' => Hash::make('password'),
                'rank' => RankEnum::TEACHER->value,
                'grade' => null,
            ]
        );

        $teacher2 = User::updateOrCreate(
            [
                'national_code' => '5555555555',
            ],
            [
                'first_name' => 'رضا',
                'last_name' => 'احمدی',
                'phone' => '09555555555',
                'is_admin' => true,
                'password' => Hash::make('password'),
                'rank' => RankEnum::TEACHER->value,
                'grade' => null,
            ]
        );

        // Create student users
        $student1 = User::updateOrCreate(
            [
                'national_code' => '6666666666',
            ],
            [
                'first_name' => 'سارا',
                'last_name' => 'کریم',
                'phone' => '09666666666',
                'is_admin' => false,
                'password' => Hash::make('password'),
                'rank' => RankEnum::STUDENT->value,
                'grade' => 10,
            ]
        );

        $student2 = User::updateOrCreate(
            [
                'national_code' => '7777777777',
            ],
            [
                'first_name' => 'محمد',
                'last_name' => 'نوری',
                'phone' => '09777777777',
                'is_admin' => false,
                'password' => Hash::make('password'),
                'rank' => RankEnum::STUDENT->value,
                'grade' => 11,
            ]
        );

        // Create test classes
        $class1 = SchoolClass::updateOrCreate(
            [
                'title' => 'دهم تجربی',
            ],
            [
                'grade' => 10,
            ]
        );

        $class2 = SchoolClass::updateOrCreate(
            [
                'title' => 'دهم ریاضی',
            ],
            [
                'grade' => 10,
            ]
        );

        $class3 = SchoolClass::updateOrCreate(
            [
                'title' => 'یازدهم تجربی',
            ],
            [
                'grade' => 11,
            ]
        );

        SchoolClass::updateOrCreate(
            [
                'title' => 'دوازدهم ریاضی',
            ],
            [
                'grade' => 12,
            ]
        );

        // Create some teacher assignments
        DB::table('class_teachers')->insertOrIgnore([
            'class_id' => $class1->id,
            'teacher_id' => $teacher1->id,
            'subject' => 'ریاضی',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('class_teachers')->insertOrIgnore([
            'class_id' => $class1->id,
            'teacher_id' => $teacher1->id,
            'subject' => 'فیزیک',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('class_teachers')->insertOrIgnore([
            'class_id' => $class2->id,
            'teacher_id' => $teacher2->id,
            'subject' => 'ریاضی',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('class_teachers')->insertOrIgnore([
            'class_id' => $class3->id,
            'teacher_id' => $teacher1->id,
            'subject' => 'ریاضی',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create some student assignments
        DB::table('class_students')->insertOrIgnore([
            'class_id' => $class1->id,
            'student_id' => $student1->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('class_students')->insertOrIgnore([
            'class_id' => $class1->id,
            'student_id' => $student2->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('class_students')->insertOrIgnore([
            'class_id' => $class2->id,
            'student_id' => $student1->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
