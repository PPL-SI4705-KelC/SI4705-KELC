<?php

namespace App\Services;

class EmissionService
{
    public function __construct(
        private TransportService $transport,
        private ConsumptionService $consumption,
        private EnergyService $energy
    ) {}

    public function calculateAll(array $transportData, array $consumptionData, array $energyData): array
    {
        $tEmission = $this->transport->calculate($transportData);
        $cEmission = $this->consumption->calculate($consumptionData);
        $eEmission = $this->energy->calculate($energyData);
        
        $total = round($tEmission + $cEmission + $eEmission, 4);

        return [
            'transport' => $tEmission,
            'consumption' => $cEmission,
            'energy' => $eEmission,
            'total' => $total,
            'sdg_score' => $this->calculateSdgScore($total),
        ];
    }

    public function calculateSdgScore(float $totalEmission): float
    {
        // Calculate based on formula: (1 - ((E_total - 5) / (50 - (-5)))) * 100
        $score = (1 - (($totalEmission - 5) / 55)) * 100;
        return round(max(0, min(100, $score)), 2);
    }
}
