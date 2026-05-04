<?php

namespace App\Services;

class CarbonCalculatorService
{
    /**
     * Emission factors (kg CO2 per unit)
     */
    private const TRANSPORT_FACTORS = [
        'car' => 0.21,        // per km
        'motorcycle' => 0.11,  // per km
        'bus' => 0.089,        // per km
        'train' => 0.041,      // per km
        'bicycle' => 0.0,      // per km
        'walking' => 0.0,      // per km
        'airplane' => 0.255,   // per km
    ];

    private const CONSUMPTION_FACTORS = [
        'meat' => 6.61,         // per kg
        'dairy' => 3.15,        // per kg
        'vegetables' => 0.37,   // per kg
        'processed_food' => 3.5, // per kg
        'clothing' => 15.0,     // per item
        'electronics' => 50.0,  // per item
    ];

    private const ENERGY_FACTORS = [
        'electricity' => 0.85,   // per kWh
        'natural_gas' => 2.0,    // per m3
        'water' => 0.419,        // per m3
        'lpg' => 2.98,           // per kg
    ];

    /**
     * Calculate transport emissions in kg CO2.
     */
    public function calculateTransport(array $data): float
    {
        $total = 0;
        foreach ($data as $type => $distance) {
            $factor = self::TRANSPORT_FACTORS[$type] ?? 0;
            $total += (float) $distance * $factor;
        }
        return round($total, 4);
    }

    /**
     * Calculate consumption emissions in kg CO2.
     */
    public function calculateConsumption(array $data): float
    {
        $total = 0;
        foreach ($data as $type => $amount) {
            $factor = self::CONSUMPTION_FACTORS[$type] ?? 0;
            $total += (float) $amount * $factor;
        }
        return round($total, 4);
    }

    /**
     * Calculate energy emissions in kg CO2.
     */
    public function calculateEnergy(array $data): float
    {
        $total = 0;
        foreach ($data as $type => $amount) {
            $factor = self::ENERGY_FACTORS[$type] ?? 0;
            $total += (float) $amount * $factor;
        }
        return round($total, 4);
    }

    /**
     * Calculate total emission.
     * E_total = E_transport + E_consumption + E_energy
     */
    public function calculateTotal(float $transport, float $consumption, float $energy): float
    {
        return round($transport + $consumption + $energy, 4);
    }

    /**
     * Calculate SDG Impact Score.
     * SDG Score = (1 - ((E_total - 5) / (50 - 5))) * 100
     * Clamped between 0 and 100.
     */
    public function calculateSdgScore(float $totalEmission): float
    {
        $score = (1 - (($totalEmission - 5) / (50 - 5))) * 100;
        return round(max(0, min(100, $score)), 2);
    }

    /**
     * Get available transport types.
     */
    public static function getTransportTypes(): array
    {
        return array_keys(self::TRANSPORT_FACTORS);
    }

    /**
     * Get available consumption types.
     */
    public static function getConsumptionTypes(): array
    {
        return array_keys(self::CONSUMPTION_FACTORS);
    }

    /**
     * Get available energy types.
     */
    public static function getEnergyTypes(): array
    {
        return array_keys(self::ENERGY_FACTORS);
    }
}
