<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('locality')->nullable()->after('district_id');
            $table->string('registration_number')->nullable()->after('industry_category');
            $table->string('contact_name')->nullable()->after('registration_number');
            $table->string('phone', 30)->nullable()->after('contact_name');
            $table->string('email')->nullable()->after('phone');
            $table->text('description')->nullable()->after('email');
            $table->string('verification_status')->default('pending')->after('description');
            $table->index('locality');
            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropIndex(['locality']);
            $table->dropIndex(['verification_status']);
            $table->dropColumn([
                'locality',
                'registration_number',
                'contact_name',
                'phone',
                'email',
                'description',
                'verification_status',
            ]);
        });
    }
};