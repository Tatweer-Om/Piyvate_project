<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'user_name' => 'admin',
                'user_email' => 'admin@example.com',
                'user_phone' => '1234567890',
                'password' => Hash::make('1234'),
                'permissions' => json_encode([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]), // Store as JSON
                'notes' => 'This is an admin user',
                'user_image' => null,
                'user_type' => 'admin',
                'branch_id' => '1',
            ],
            [
                'user_name' => 'user',
                'user_email' => 'user@example.com',
                'user_phone' => '9876543210',
                'password' => Hash::make('1234'),
                'permissions' => json_encode([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]), // Store as JSON
                'notes' => 'This is a regular user',
                'user_image' => null,
                'user_type' => 'user',
                'branch_id' => '2',
            ],
        ]);

    }

}
