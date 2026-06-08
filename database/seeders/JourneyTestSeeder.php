<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Emission;
use App\Models\User;
use App\Models\XpLog;
use App\Services\CarbonCalculatorService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JourneyTestSeeder extends Seeder
{
    public function run(): void
    {
        $calculator = new CarbonCalculatorService();

        $user = User::create([
            'name' => 'Journey Test User',
            'username' => 'journey_test',
            'email' => 'journey_test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'xp' => 2600,
            'level' => 3,
            'avatar' => null,
            'bio' => 'Dummy user for journey leveling and 7-day carbon activity testing.',
        ]);

        $activityData = [
            [
                'date' => Carbon::today()->subDays(6),
                'transport' => ['bus' => 20, 'walking' => 3],
                'consumption' => ['meat' => 0.2, 'vegetables' => 1.2, 'dairy' => 0.3],
                'energy' => ['electricity' => 8, 'water' => 1.5],
            ],
            [
                'date' => Carbon::today()->subDays(5),
                'transport' => ['train' => 15, 'walking' => 2],
                'consumption' => ['meat' => 0.15, 'vegetables' => 1.5, 'dairy' => 0.2],
                'energy' => ['electricity' => 7, 'water' => 1.8],
            ],
            [
                'date' => Carbon::today()->subDays(4),
                'transport' => ['motorcycle' => 12],
                'consumption' => ['meat' => 0.1, 'vegetables' => 1.8, 'dairy' => 0.25],
                'energy' => ['electricity' => 6.5, 'water' => 1.4],
            ],
            [
                'date' => Carbon::today()->subDays(3),
                'transport' => ['bus' => 18, 'walking' => 4],
                'consumption' => ['meat' => 0.25, 'vegetables' => 1.0, 'dairy' => 0.35],
                'energy' => ['electricity' => 9, 'water' => 1.7],
            ],
            [
                'date' => Carbon::today()->subDays(2),
                'transport' => ['walking' => 5],
                'consumption' => ['meat' => 0.05, 'vegetables' => 2.0, 'dairy' => 0.1],
                'energy' => ['electricity' => 5.5, 'water' => 1.2],
            ],
            [
                'date' => Carbon::today()->subDays(1),
                'transport' => ['train' => 25],
                'consumption' => ['meat' => 0.18, 'vegetables' => 1.3, 'dairy' => 0.22],
                'energy' => ['electricity' => 7.5, 'water' => 1.6],
            ],
            [
                'date' => Carbon::today(),
                'transport' => ['motorcycle' => 10],
                'consumption' => ['meat' => 0.12, 'vegetables' => 1.7, 'dairy' => 0.2],
                'energy' => ['electricity' => 6, 'water' => 1.5],
            ],
        ];

        foreach ($activityData as $day) {
            $transportEmission = $calculator->calculateTransport($day['transport']);
            $consumptionEmission = $calculator->calculateConsumption($day['consumption']);
            $energyEmission = $calculator->calculateEnergy($day['energy']);
            $totalEmission = $calculator->calculateTotal($transportEmission, $consumptionEmission, $energyEmission);
            $sdgScore = $calculator->calculateSdgScore($totalEmission);

            $activity = Activity::create([
                'user_id' => $user->id,
                'activity_date' => $day['date'],
                'transport_data' => $day['transport'],
                'consumption_data' => $day['consumption'],
                'energy_data' => $day['energy'],
            ]);

            Emission::create([
                'user_id' => $user->id,
                'activity_id' => $activity->id,
                'transport_emission' => $transportEmission,
                'consumption_emission' => $consumptionEmission,
                'energy_emission' => $energyEmission,
                'total_emission' => $totalEmission,
                'sdg_score' => $sdgScore,
                'emission_date' => $day['date'],
                'raw_input' => [
                    'transport' => $day['transport'],
                    'consumption' => $day['consumption'],
                    'energy' => $day['energy'],
                ],
            ]);
        }

        XpLog::create([
            'user_id' => $user->id,
            'xp_amount' => 1000,
            'source' => 'journey',
            'description' => 'Completed 7-day carbon tracking challenge',
        ]);

        XpLog::create([
            'user_id' => $user->id,
            'xp_amount' => 800,
            'source' => 'emission_calculation',
            'description' => 'Scored carbon reduction consistency over one week',
        ]);
    }
}
