<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rw_accesses', function (Blueprint $table) {
            $table->id();
            $table->string('rw')->nullable(); // RW number (1-6) or NULL for superadmin
            $table->string('kode_akses')->unique(); // Unique access code
            $table->string('nama_posyandu')->nullable(); // Posyandu name
            $table->boolean('status')->default(true); // Active/inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rw_accesses');
    }
};
