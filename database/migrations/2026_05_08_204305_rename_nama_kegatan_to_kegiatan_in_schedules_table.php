<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {

            // Hapus kolom typo
            if (Schema::hasColumn('schedules', 'kegatan')) {
                $table->dropColumn('kegatan');
            }

            if (Schema::hasColumn('schedules', 'nama_kegatan')) {
                $table->dropColumn('nama_kegatan');
            }

        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {

            $table->string('kegatan')->nullable();
            $table->string('nama_kegatan')->nullable();

        });
    }
};