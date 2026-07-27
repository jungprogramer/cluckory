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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@cluckory.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Regular User
        User::create([
            'name' => 'Regular User',
            'email' => 'user@cluckory.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567891',
        ]);
    }
}