<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Product;
use App\Models\Business;
use App\Models\MarketDemand;
use App\Models\ProductionCapacity;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Districts
        $purbaMedinipur = District::create([
            'name' => 'Purba Medinipur',
            'state' => 'West Bengal',
            'latitude' => 21.9360,
            'longitude' => 87.7770,
        ]);

        $howrah = District::create([
            'name' => 'Howrah',
            'state' => 'West Bengal',
            'latitude' => 22.5958,
            'longitude' => 88.2636,
        ]);

        // 2. Seed Products
        $packaging = Product::create([
            'name' => 'Industrial Packaging Boxes',
            'category' => 'Packaging',
            'unit' => 'Units',
            'hsn_code' => '481910',
        ]);

        $agroProcessing = Product::create([
            'name' => 'Processed Agro Flour & Feed',
            'category' => 'Agro-processing',
            'unit' => 'Tons',
            'hsn_code' => '110100',
        ]);

        $electronicComponents = Product::create([
            'name' => 'Wire Harness & Connectors',
            'category' => 'Electrical',
            'unit' => 'Units',
            'hsn_code' => '854430',
        ]);

        // 3. Seed Businesses (MSMEs)
        Business::create([
            'district_id' => $purbaMedinipur->id,
            'name' => 'Medinipur Corrugation Works',
            'industry_category' => 'Packaging',
            'machinery_specs' => ['semi_automatic_corrugation' => true, 'capacity_per_day' => 400],
            'latitude' => 21.9400,
            'longitude' => 87.7800,
        ]);

        Business::create([
            'district_id' => $purbaMedinipur->id,
            'name' => 'Tamluk Kraft Containers',
            'industry_category' => 'Packaging',
            'machinery_specs' => ['rotary_cutting_machine' => true, 'capacity_per_day' => 300],
            'latitude' => 22.3000,
            'longitude' => 87.9200,
        ]);

        Business::create([
            'district_id' => $howrah->id,
            'name' => 'Howrah Precision Harness',
            'industry_category' => 'Electrical',
            'machinery_specs' => ['wire_stripping' => true, 'crimping_press' => true],
            'latitude' => 22.6000,
            'longitude' => 88.2700,
        ]);

        // 4. Seed Market Demand (e.g. 500,000 units packaging demand in Purba Medinipur)
        MarketDemand::create([
            'district_id' => $purbaMedinipur->id,
            'product_id' => $packaging->id,
            'period' => '2026-Annual',
            'quantity' => 500000.00,
            'source' => 'District Industrial Centre Survey 2026',
            'verification_status' => 'verified',
        ]);

        MarketDemand::create([
            'district_id' => $howrah->id,
            'product_id' => $electronicComponents->id,
            'period' => '2026-Annual',
            'quantity' => 85000.00,
            'source' => 'Cluster Procurement Board',
            'verification_status' => 'verified',
        ]);

        // 5. Seed Production Capacity (e.g. local supply is only 120,000 units -> Gap is 380,000)
        ProductionCapacity::create([
            'district_id' => $purbaMedinipur->id,
            'product_id' => $packaging->id,
            'period' => '2026-Annual',
            'quantity' => 120000.00,
            'verification_status' => 'verified',
        ]);

        ProductionCapacity::create([
            'district_id' => $howrah->id,
            'product_id' => $electronicComponents->id,
            'period' => '2026-Annual',
            'quantity' => 70000.00,
            'verification_status' => 'verified',
        ]);
    }
}