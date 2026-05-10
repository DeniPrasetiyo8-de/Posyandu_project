<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
/**
     * Run the migrations.
     * Add fields needed for API and Posyandu functionality
     * Note: phone, address, rt, rw columns already added by 2024_10_12_000001_add_details_to_users_table
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only change role default - other columns already exist from previous migration
            $table->string('role')->default('user')->change();
        });
    }

/**
     * Reverse the migrations.
     * Note: We only revert the role default change - other columns belong to previous migration
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only revert role default - other columns handled by 2024_10_12_000001_add_details_to_users_table
            $table->string('role')->default('orang_tua')->change();
        });
    }
};
