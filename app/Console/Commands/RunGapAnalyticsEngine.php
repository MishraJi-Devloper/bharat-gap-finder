<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MarketDemand;
use App\Services\GapCalculationService;
use App\Services\OpportunityScoringService;

class RunGapAnalyticsEngine extends Command
{
    protected $signature = 'bmgf:run-engine';
    protected $description = 'Calculates district-level manufacturing deficits and computes opportunity scores';

    public function handle(GapCalculationService $gapService, OpportunityScoringService $scoreService)
    {
        $this->info('Starting Bharat Manufacturing Gap Finder Analytics Engine...');

        $demands = MarketDemand::all();

        foreach ($demands as $demand) {
            $gap = $gapService->calculateForProductAndDistrict($demand->district_id, $demand->product_id, $demand->period);

            if ($gap) {
                $scoredGap = $scoreService->computeScore($gap);
                $this->line("Identified Gap: [{$scoredGap->product->name}] in [{$scoredGap->district->name}] - Deficit: {$scoredGap->estimated_gap} units | Score: {$scoredGap->opportunity_score}/100");
            }
        }

        $this->info('Analytics pipeline completed successfully!');
    }
}