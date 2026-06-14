<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_posyandu', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->enum('kategori', ['anak', 'ibu_hamil', 'kegatan', 'imunisasi', 'gizi', 'stunting', 'umum'])->default('umum');
            $table->foreignId('posyandu_id')->nullable()->constrained('posyandus')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('konten')->nullable();
            $table->text('data_json')->nullable(); // Store serialized report data
            $table->string('bulan')->nullable(); // Format: YYYY-MM
            $table->string('tahun')->nullable(); // Format: YYYY
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            
            $table->index(['kategori', 'bulan']);
            $table->index(['posyandu_id', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_posyandu');
    }
};
