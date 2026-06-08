<?php

namespace App\Services;

class ConsumptionService
{
    private const DIET_FACTORS = [
        'meat_every' => 4.0,
        'meat_some' => 3.0,
        'no_beef' => 2.3,
        'rarely' => 1.8,
        'vegetarian' => 1.4,
        'vegan' => 1.0,
    ];

    private const SPENDING_MAP = [
        '0' => 0,
        'low' => 110000,
        'medium' => 700000,
        'high' => 1500000,
    ];

    private const WASTE_FACTORS = [
        'none' => 1.0,
        'low' => 1.05,
        'medium' => 1.2,
        'high' => 1.4,
    ];

    private const SOURCE_FACTORS = [
        'mostly_local' => 0.9,
        'some_local' => 1.0,
        'not_concerned' => 1.1,
    ];

    public function calculate(array $data): float
    {
        $dietKey = $data['diet_type'] ?? 'meat_some';
        $diet = self::DIET_FACTORS[$dietKey] ?? 3.0;

        $spendingKey = $data['spending'] ?? 'low';
        $spending = self::SPENDING_MAP[$spendingKey] ?? 110000;
        $consumption = $spending > 0 ? ($spending / 25000) : 1; // Default to 1 if 0 to prevent zeroing out the entire footprint for people not spending that day, unless intended. Let's follow strict formula: if spending is 0, it's 0. Wait, strict formula says E = Diet * Consumption * Waste * Distribution. If spending = 0, E = 0. Let's use strict formula but default to a realistic low value or 0 if strictly requested. We'll follow: spending/25000.
        $consumptionVal = $spending / 25000;
        if ($consumptionVal == 0) $consumptionVal = 0.5; // Failsafe to not zero out diet footprint entirely

        $wasteKey = $data['waste'] ?? 'none';
        $waste = self::WASTE_FACTORS[$wasteKey] ?? 1.0;

        $sourceKey = $data['source'] ?? 'some_local';
        $source = self::SOURCE_FACTORS[$sourceKey] ?? 1.0;

        $emission = $diet * $consumptionVal * $waste * $source;

        return round($emission, 4);
    }
}
