<?php

namespace Database\Seeders;

use App\Enums\RankEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'national_code' => '1234567890',
            ],
            [
                'first_name' => 'Developer',
                'last_name' => 'Admin',
                'phone' => '09387511748',
                'is_admin' => true,
                'password' => Hash::make('password'),
                'rank' => RankEnum::MANAGER->value,
                'grade' => null,
            ]
        );
    }
}
