<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmissionRecord;
use App\Models\User;
use Carbon\Carbon;

class EmissionSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada setidaknya 1 user untuk dihubungkan dengan data emisi
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Generate data dummy untuk 7 hari terakhir agar chart dan riwayat terlihat bagus
        $activities = ['Listrik', 'Transportasi'];

        for ($i = 0; $i < 20; $i++) {
            $randomDaysAgo = rand(0, 6); // Acak antara hari ini (0) sampai 6 hari lalu
            $type = $activities[array_rand($activities)];
            $amount = rand(5, 50);
            
            // Perhitungan faktor dampak karbon (dummy)
            $factor = ($type === 'Transportasi') ? 2.3 : 0.85;
            
            EmissionRecord::create([
                'user_id' => $user->id,
                'activity_type' => $type,
                'amount_value' => $amount,
                'carbon_impact' => round($amount * $factor, 2),
                'recorded_at' => Carbon::today()->subDays($randomDaysAgo)->toDateString(),
            ]);
        }
    }
}
