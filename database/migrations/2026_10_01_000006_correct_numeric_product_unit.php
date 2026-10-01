<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')
            ->where('name', 'Solar panel')
            ->where('unit', '50000')
            ->update(['unit' => 'Panels']);
    }

    public function down(): void
    {
        DB::table('products')
            ->where('name', 'Solar panel')
            ->where('unit', 'Panels')
            ->update(['unit' => '50000']);
    }
};