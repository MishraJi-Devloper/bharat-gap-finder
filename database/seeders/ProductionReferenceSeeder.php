<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductionReferenceSeeder extends Seeder
{
    public function run(): void
    {
        District::firstOrCreate(
            ['name' => 'Purba Medinipur', 'state' => 'West Bengal'],
            ['latitude' => 21.9360, 'longitude' => 87.7770]
        );

        District::firstOrCreate(
            ['name' => 'Howrah', 'state' => 'West Bengal'],
            ['latitude' => 22.5958, 'longitude' => 88.2636]
        );

        Product::firstOrCreate(
            ['name' => 'Industrial Packaging Boxes'],
            ['category' => 'Packaging', 'unit' => 'Units', 'hsn_code' => '481910']
        );

        Product::firstOrCreate(
            ['name' => 'Processed Agro Flour & Feed'],
            ['category' => 'Agro-processing', 'unit' => 'Tons', 'hsn_code' => '110100']
        );

        Product::firstOrCreate(
            ['name' => 'Wire Harness & Connectors'],
            ['category' => 'Electrical', 'unit' => 'Units', 'hsn_code' => '854430']
        );
    }
}
