<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('businesses')
            ->whereNull('user_id')
            ->update(['verification_status' => 'verified']);
    }

    public function down(): void
    {
        DB::table('businesses')
            ->whereNull('user_id')
            ->update(['verification_status' => 'pending']);
    }
};