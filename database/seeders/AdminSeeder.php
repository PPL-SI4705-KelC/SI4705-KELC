<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Act4Climate',
                'email' => 'admin@act4climate.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'xp' => 0,
                'level' => 1,
                'total_point' => 0,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['username' => 'demo'],
            [
                'name' => 'Demo User',
                'email' => 'user@act4climate.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'xp' => 1500,
                'level' => 2,
                'total_point' => 0,
                'email_verified_at' => now(),
            ]
        );
    }
}
