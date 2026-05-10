<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * NOTE: Fixed to check if columns already exist to avoid duplicate column errors.
     * The original schedules table already has 'kegatan' column created.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Only add if columns don't exist yet
            if (!Schema::hasColumn('schedules', 'kegatan')) {
                $table->string('kegatan')->nullable()->after('posyandu_id');
            }
            if (!Schema::hasColumn('schedules', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('tanggal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['kegatan', 'deskripsi']);
        });
    }
};
