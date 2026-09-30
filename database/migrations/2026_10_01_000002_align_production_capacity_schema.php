<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_capacity', function (Blueprint $table) {
            $table->foreignUuid('business_id')->nullable()->after('product_id');
            $table->decimal('installed_capacity', 15, 2)->nullable()->after('quantity');
            $table->decimal('actual_production', 15, 2)->nullable()->after('installed_capacity');
            $table->index('business_id');
        });
    }

    public function down(): void
    {
        Schema::table('production_capacity', function (Blueprint $table) {
            $table->dropIndex(['business_id']);
            $table->dropForeign(['business_id']);
            $table->dropColumn(['business_id', 'installed_capacity', 'actual_production']);
        });
    }
};