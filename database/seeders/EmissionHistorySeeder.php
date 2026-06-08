<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activity;
use App\Models\Emission;
use App\Services\EmissionService;
use Carbon\Carbon;

class EmissionHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(EmissionService $calculator): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Hapus data lama agar tidak menumpuk
            Activity::where('user_id', $user->id)->delete();
            Emission::where('user_id', $user->id)->delete();

            // Insert data 14 hari ke belakang
            for ($i = 14; $i >= 0; $i--) {
                $date = Carbon::now('Asia/Jakarta')->subDays($i)->toDateString();

                // Simulasi tren: 7 hari sebelumnya emisi lebih tinggi, 7 hari terakhir mulai turun
                // supaya trend nya menunjukkan 'penurunan emisi' (hijau/panah bawah)
                if ($i > 7) {
                    $transportData = ['distance' => rand(15, 25), 'vehicle_type' => 'car'];
                    $consumptionData = ['diet_type' => 'meat_heavy', 'food_waste' => rand(2, 4)];
                    $energyData = ['electricity_kwh' => rand(5, 8)];
                } else {
                    $transportData = ['distance' => rand(5, 10), 'vehicle_type' => 'motorcycle'];
                    $consumptionData = ['diet_type' => 'balanced', 'food_waste' => rand(0, 1)];
                    $energyData = ['electricity_kwh' => rand(2, 4)];
                }

                $activity = Activity::create([
                    'user_id' => $user->id,
                    'activity_date' => $date,
                    'transport_data' => $transportData,
                    'consumption_data' => $consumptionData,
                    'energy_data' => $energyData,
                ]);

                $results = $calculator->calculateAll($transportData, $consumptionData, $energyData);

                Emission::create([
                    'user_id' => $user->id,
                    'activity_id' => $activity->id,
                    'transport_emission' => $results['transport'],
                    'consumption_emission' => $results['consumption'],
                    'energy_emission' => $results['energy'],
                    'total_emission' => $results['total'],
                    'sdg_score' => $results['sdg_score'],
                    'emission_date' => $date,
                    'raw_input' => [
                        'transport' => $transportData, 
                        'consumption' => $consumptionData, 
                        'energy' => $energyData
                    ],
                ]);
            }
        }
    }
}
