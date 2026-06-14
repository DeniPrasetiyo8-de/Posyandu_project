<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip_data', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_data'); // 'anak', 'ibu_hamil', 'kader', 'jadwal', 'kms'
            $table->foreignId('data_id')->nullable(); // Reference to original table ID
            $table->foreignId('posyandu_id')->nullable()->constrained('posyandus')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('data_json'); // Store full data snapshot
            $table->string('arsip_tahun')->nullable(); // Year of archive
            $table->string('arsip_bulan')->nullable(); // Month of archive
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->index(['jenis_data', 'arsip_tahun']);
            $table->index(['posyandu_id', 'arsip_tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_data');
    }
};
