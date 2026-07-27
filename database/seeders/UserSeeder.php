<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@cluckory.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        // Admin 2
        User::updateOrCreate(
            ['email' => 'admin2@cluckory.com'],
            [
                'name' => 'Admin 2',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567892',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin3@cluckory.com'],
            [
                'name' => 'Admin 3',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567892',
            ]
        );

        // Regular User
        User::updateOrCreate(
            ['email' => 'user@cluckory.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '081234567891',
            ]
        );
    }
}