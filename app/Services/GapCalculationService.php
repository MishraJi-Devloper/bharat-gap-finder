<?php

namespace App\Services;

use App\Models\MarketDemand;
use App\Models\ProductionCapacity;
use App\Models\ManufacturingGap;

class GapCalculationService
{
    /**
     * Net Supply Deficit = max(0, Demand - Production)
     */
    public function calculateForProductAndDistrict(string $districtId, string $productId, string $period = '2026-Annual'): ?ManufacturingGap
    {
        // Demand query
        $demand = MarketDemand::where('district_id', $districtId)
            ->where('product_id', $productId)
            ->where('period', $period)
            ->sum('quantity');

        // Local production query
        $production = ProductionCapacity::where('district_id', $districtId)
            ->where('product_id', $productId)
            ->where('period', $period)
            ->sum('quantity');

        // Non-negative gap logic
        $estimatedGap = max(0, $demand - $production);

        if ($estimatedGap <= 0) {
            return null; // Local supply meets or exceeds demand
        }

        // Save or update gap record
        return ManufacturingGap::updateOrCreate(
            [
                'district_id' => $districtId,
                'product_id' => $productId,
            ],
            [
                'estimated_gap' => $estimatedGap,
                'status' => 'published',
            ]
        );
    }
}