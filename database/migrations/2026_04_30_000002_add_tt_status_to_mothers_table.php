<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mothers', function (Blueprint $table) {
            // Status TT (Tablet Tambah Darah)
            $table->enum('tt1', ['Sudah', 'Belum'])->default('Belum')->after('status_kesehatan');
            $table->enum('tt2', ['Sudah', 'Belum'])->default('Belum')->after('tt1');
            $table->enum('tt3', ['Sudah', 'Belum'])->default('Belum')->after('tt2');
            $table->enum('tt4', ['Sudah', 'Belum'])->default('Belum')->after('tt3');
            $table->enum('tt5', ['Sudah', 'Belum'])->default('Belum')->after('tt4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mothers', function (Blueprint $table) {
            $table->dropColumn([
                'tt1',
                'tt2',
                'tt3',
                'tt4',
                'tt5',
            ]);
        });
    }
};
