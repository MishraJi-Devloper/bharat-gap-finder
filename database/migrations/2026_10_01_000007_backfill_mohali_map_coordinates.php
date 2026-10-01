<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('districts')
            ->where('name', 'Mohali')
            ->where('state', 'Punjab')
            ->whereNull('latitude')
            ->whereNull('longitude')
            ->update([
                'latitude' => 30.7046,
                'longitude' => 76.7179,
            ]);
    }

    public function down(): void
    {
        DB::table('districts')
            ->where('name', 'Mohali')
            ->where('state', 'Punjab')
            ->where('latitude', 30.7046)
            ->where('longitude', 76.7179)
            ->update([
                'latitude' => null,
                'longitude' => null,
            ]);
    }
};
