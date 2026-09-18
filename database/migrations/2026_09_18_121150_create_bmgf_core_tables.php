<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Districts Table
        Schema::create('districts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('state')->default('West Bengal');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });

        // 2. Products Taxonomy Table
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('category'); // Packaging, Agro, Electrical, etc.
            $table->string('unit'); // Units, Tons, Meters
            $table->string('hsn_code')->nullable();
            $table->timestamps();
        });

        // 3. Businesses (MSMEs) Table
        Schema::create('businesses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('district_id')->constrained('districts')->onDelete('cascade');
            $table->string('name');
            $table->string('industry_category');
            $table->json('machinery_specs')->nullable(); // JSON/JSONB compatible
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });

        // 4. Market Demand Signals Table
        Schema::create('market_demand', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('district_id')->constrained('districts')->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->string('period')->default('2026-Annual');
            $table->decimal('quantity', 15, 2);
            $table->string('source')->nullable();
            $table->string('verification_status')->default('pending');
            $table->timestamps();
        });

        // 5. Local Production Capacity Table
        Schema::create('production_capacity', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('district_id')->constrained('districts')->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->string('period')->default('2026-Annual');
            $table->decimal('quantity', 15, 2);
            $table->string('verification_status')->default('verified');
            $table->timestamps();
        });

        // 6. Manufacturing Gaps & Scores Table
        Schema::create('manufacturing_gaps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('district_id')->constrained('districts')->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('estimated_gap', 15, 2);
            $table->decimal('opportunity_score', 5, 2)->default(0);
            $table->json('score_breakdown')->nullable();
            $table->json('barriers')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manufacturing_gaps');
        Schema::dropIfExists('production_capacity');
        Schema::dropIfExists('market_demand');
        Schema::dropIfExists('businesses');
        Schema::dropIfExists('products');
        Schema::dropIfExists('districts');
    }
};