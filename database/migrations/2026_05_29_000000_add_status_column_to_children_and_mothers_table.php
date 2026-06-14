<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom status AKTIF/NONAKTIF untuk children dan mothers
     */
    public function up(): void
    {
        // Tambahkan kolom status ke tabel children
        Schema::table('children', function (Blueprint $table) {
            $table->enum('status', ['AKTIF', 'NONAKTIF'])->default('AKTIF')->after('vitamin_a_12_59')->nullable();
        });

        // Tambahkan kolom status ke tabel mothers
        Schema::table('mothers', function (Blueprint $table) {
            $table->enum('status', ['AKTIF', 'NONAKTIF'])->default('AKTIF')->after('tt5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('mothers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
