<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            QuizSeeder::class,
            CommunitySeeder::class,
<<<<<<< HEAD
        ]);
=======
            EmissionSeeder::class,
            JourneyTestSeeder::class,
      ]);
>>>>>>> ac7a16f12a0ab597fb817dc8f456037e0ba9679f
    }
}
