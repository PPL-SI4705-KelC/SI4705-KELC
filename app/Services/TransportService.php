<?php

namespace App\Services;

class TransportService
{
    private const DURATIONS = [
        'none' => 0,
        'lt_1' => 0.5,
        '1_to_2' => 1.5,
        '2_to_4' => 3,
        'gt_4' => 5,
    ];

    private const FLIGHT_DURATIONS = [
        'none' => 0,
        'lt_1' => 0.5,
        '1_to_3' => 2,
        '3_to_6' => 4.5,
        'gt_6' => 8,
    ];

    private const SPEEDS = [
        'car' => 30,
        'motorcycle' => 35,
        'train' => 60,
        'bus' => 40,
        'airplane' => 800,
    ];

    private const CAR_FACTORS = [
        'electric' => 0.05,
        'plugin_hybrid' => 0.10,
        'hybrid' => 0.12,
        'small' => 0.17,
        'medium' => 0.20,
        'large' => 0.25,
    ];

    private const MOTO_FACTORS = [
        'electric' => 0.03,
        'gasoline' => 0.10,
    ];

    public function calculate(array $data): float
    {
        $emission = 0;

        // 1. Main Vehicle(s) (Car/Motorcycle)
        $mainVehicles = $data['main_vehicle'] ?? [];
        if (!is_array($mainVehicles)) {
            $mainVehicles = [$mainVehicles];
        }

        foreach ($mainVehicles as $vehicle) {
            if ($vehicle === 'car') {
                $durationKey = $data['car_duration'] ?? 'none';
                $duration = self::DURATIONS[$durationKey] ?? 0;
                $speed = self::SPEEDS['car'] ?? 0;
                $type = $data['car_type'] ?? 'medium';
                $factor = self::CAR_FACTORS[$type] ?? 0.20;
                
                $emission += ($duration * $speed * $factor);
            } elseif ($vehicle === 'motorcycle') {
                $durationKey = $data['motorcycle_duration'] ?? 'none';
                $duration = self::DURATIONS[$durationKey] ?? 0;
                $speed = self::SPEEDS['motorcycle'] ?? 0;
                $type = $data['motorcycle_type'] ?? 'gasoline';
                $factor = self::MOTO_FACTORS[$type] ?? 0.10;
                
                $emission += ($duration * $speed * $factor);
            }
        }

        // 2. Train
        $trainDurKey = $data['train_duration'] ?? 'none';
        $trainDur = self::DURATIONS[$trainDurKey] ?? 0;
        $emission += ($trainDur * self::SPEEDS['train'] * 0.04);

        // 3. Bus
        $busDurKey = $data['bus_duration'] ?? 'none';
        $busDur = self::DURATIONS[$busDurKey] ?? 0;
        $emission += ($busDur * self::SPEEDS['bus'] * 0.08);

        // 4. Air Travel
        $flightDurKey = $data['flight_duration'] ?? 'none';
        $flightDur = self::FLIGHT_DURATIONS[$flightDurKey] ?? 0;
        $emission += ($flightDur * self::SPEEDS['airplane'] * 0.15);

        return round($emission, 4);
    }
}
