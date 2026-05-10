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
        Schema::table('children', function (Blueprint $table) {
            // Status Imunisasi
            $table->enum('imunisasi_hb0', ['Sudah', 'Belum'])->default('Belum')->after('tinggi_badan');
            $table->enum('imunisasi_bcg', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_hb0');
            $table->enum('imunisasi_polio1', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_bcg');
            $table->enum('imunisasi_polio2', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_polio1');
            $table->enum('imunisasi_polio3', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_polio2');
            $table->enum('imunisasi_polio4', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_polio3');
            $table->enum('imunisasi_dpt_hb_hib1', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_polio4');
            $table->enum('imunisasi_dpt_hb_hib2', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_dpt_hb_hib1');
            $table->enum('imunisasi_dpt_hb_hib3', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_dpt_hb_hib2');
            $table->enum('imunisasi_campak', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_dpt_hb_hib3');
            
            // Status Vitamin
            $table->enum('vitamin_a_6_11', ['Sudah', 'Belum'])->default('Belum')->after('imunisasi_campak');
            $table->enum('vitamin_a_12_59', ['Sudah', 'Belum'])->default('Belum')->after('vitamin_a_6_11');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn([
                'imunisasi_hb0',
                'imunisasi_bcg',
                'imunisasi_polio1',
                'imunisasi_polio2',
                'imunisasi_polio3',
                'imunisasi_polio4',
                'imunisasi_dpt_hb_hib1',
                'imunisasi_dpt_hb_hib2',
                'imunisasi_dpt_hb_hib3',
                'imunisasi_campak',
                'vitamin_a_6_11',
                'vitamin_a_12_59',
            ]);
        });
    }
};
