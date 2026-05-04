<?php

namespace App\Services;

class EnergyService
{
    private const RESIDENCE_FACTORS = [
        'detached' => 1.2,
        'row' => 1.0,
        'apartment' => 0.8,
    ];

    private const BEDROOM_FACTORS = [
        '1' => 0.8,
        '2' => 1.0,
        '3' => 1.2,
        '4_plus' => 1.4,
    ];

    private const ADULT_FACTORS = [
        '1' => 1.4,
        '2' => 1.2,
        '3' => 1.0,
        '4' => 0.9,
        '5_plus' => 0.8,
    ];

    private const ELEC_SOURCE_FACTORS = [
        'pln' => 0.85,
        'generator' => 1.2,
    ];

    private const BEHAVIOR_FACTORS = [
        'yes' => 0.9,
        'no' => 1.1,
    ];

    private const COOLING_FACTORS = [
        'none' => 2,
        'fan' => 3,
        'ac_lt_8' => 7,
        'ac_gt_8' => 12,
    ];

    public function calculate(array $data): float
    {
        $residence = self::RESIDENCE_FACTORS[$data['residence'] ?? 'row'] ?? 1.0;
        $bedrooms = self::BEDROOM_FACTORS[$data['bedrooms'] ?? '2'] ?? 1.0;
        $adults = self::ADULT_FACTORS[$data['adults'] ?? '2'] ?? 1.2;
        $electricity = self::ELEC_SOURCE_FACTORS[$data['electricity'] ?? 'pln'] ?? 0.85;
        $behavior = self::BEHAVIOR_FACTORS[$data['energy_saving'] ?? 'yes'] ?? 0.9;
        $cooling = self::COOLING_FACTORS[$data['cooling'] ?? 'fan'] ?? 3;

        $emission = $residence * $bedrooms * $adults * $electricity * $behavior * $cooling;

        return round($emission, 4);
    }
}
