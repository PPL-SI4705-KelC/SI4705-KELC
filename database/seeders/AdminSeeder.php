<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Act4Climate',
            'username' => 'admin',
            'email' => 'admin@act4climate.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'xp' => 0,
            'level' => 1,
            'total_point' => 0,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Demo User',
            'username' => 'demo',
            'email' => 'user@act4climate.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'xp' => 1500,
            'level' => 2,
            'total_point' => 0,
            'email_verified_at' => now(),
        ]);
    }
}
