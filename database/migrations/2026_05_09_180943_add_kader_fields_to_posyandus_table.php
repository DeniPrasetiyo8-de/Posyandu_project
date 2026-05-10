<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posyandus', function (Blueprint $table) {
            $table->string('nama_kader')->nullable()->after('nama_posyandu');
            $table->string('alamat')->nullable()->after('nama_kader');
            $table->string('status_kehadiran')->default('hadir')->after('alamat');
            $table->string('foto')->nullable()->after('status_kehadiran');
        });
    }

    public function down(): void
    {
        Schema::table('posyandus', function (Blueprint $table) {
            $table->dropColumn(['nama_kader', 'alamat', 'status_kehadiran', 'foto']);
        });
    }
};
