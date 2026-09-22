<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manufacturing_gaps', function (Blueprint $table) {
            $table->index('district_id');
            $table->index('product_id');
            $table->index('opportunity_score');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->index('district_id');
        });
    }

    public function down(): void
    {
        Schema::table('manufacturing_gaps', function (Blueprint $table) {
            $table->dropIndex(['district_id']);
            $table->dropIndex(['product_id']);
            $table->dropIndex(['opportunity_score']);
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropIndex(['district_id']);
        });
    }
};