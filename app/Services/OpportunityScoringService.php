<?php

namespace App\Services;

use App\Models\ManufacturingGap;

class OpportunityScoringService
{
    /**
     * Compute normalized 0-100 weighted opportunity score
     */
    public function computeScore(ManufacturingGap $gap, array $factorOverrides = []): ManufacturingGap
    {
        // Default factor calibrations (0 - 100) based on district assessment
        $demandScore = $factorOverrides['demand_strength'] ?? 85.0; // D
        $supplyScore = $factorOverrides['supply_deficit'] ?? 90.0;  // S
        $rawMaterialScore = $factorOverrides['raw_material'] ?? 80.0; // R
        $skillsScore = $factorOverrides['skills_availability'] ?? 65.0; // K
        $infraScore = $factorOverrides['infra_readiness'] ?? 70.0; // I
        $buyerScore = $factorOverrides['buyer_presence'] ?? 80.0; // B

        // Weighted formula
        $totalScore = (0.30 * $demandScore)
                    + (0.25 * $supplyScore)
                    + (0.15 * $rawMaterialScore)
                    + (0.10 * $skillsScore)
                    + (0.10 * $infraScore)
                    + (0.10 * $buyerScore);

        $gap->opportunity_score = round($totalScore, 2);
        $gap->score_breakdown = [
            'demand_strength' => $demandScore,
            'supply_deficit' => $supplyScore,
            'raw_material' => $rawMaterialScore,
            'skills_availability' => $skillsScore,
            'infra_readiness' => $infraScore,
            'buyer_presence' => $buyerScore,
        ];

        // Structural bottlenecks/barriers
        $gap->barriers = [
            'raw_material_access' => 'High (Local availability confirmed)',
            'skills_availability' => 'Medium (Operator upskilling needed)',
            'infra_readiness' => 'Medium (Consistent 3-phase industrial power)',
        ];

        $gap->save();

        return $gap;
    }
}