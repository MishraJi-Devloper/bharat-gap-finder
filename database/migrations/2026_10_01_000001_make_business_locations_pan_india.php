<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->foreignUuid('district_id')->nullable()->change();
            $table->string('district_name')->nullable()->after('district_id');
            $table->string('state')->nullable()->after('district_name');
            $table->index(['state', 'district_name']);
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropIndex(['state', 'district_name']);
            $table->dropColumn(['district_name', 'state']);
            $table->foreignUuid('district_id')->nullable(false)->change();
        });
    }
};