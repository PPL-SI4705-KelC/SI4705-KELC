<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
=======
>>>>>>> 14f647be00457c2be938ca3977220a2674dc60a5
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
<<<<<<< HEAD
    use WithoutModelEvents;

=======
>>>>>>> 14f647be00457c2be938ca3977220a2674dc60a5
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            EmissionSeeder::class,
=======
        $this->call([
            AdminSeeder::class,
            QuizSeeder::class,
            CommunitySeeder::class,
            JourneyTestSeeder::class,
>>>>>>> 14f647be00457c2be938ca3977220a2674dc60a5
        ]);
    }
}
