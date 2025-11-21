<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        // Admin
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone_number' => '0700000000',
            'citizenship' => 'Kenyan',
            'role' => 'admin',
            'is_verified' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Normal User 1
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone_number' => '0712345678',
            'citizenship' => 'Kenyan',
            'role' => 'user',
            'is_verified' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Normal User 2
        User::create([
            'first_name' => 'Mary',
            'last_name' => 'Wanjiku',
            'email' => 'mary@example.com',
            'phone_number' => '0798765432',
            'citizenship' => 'Kenyan',
            'role' => 'user',
            'is_verified' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }
}

