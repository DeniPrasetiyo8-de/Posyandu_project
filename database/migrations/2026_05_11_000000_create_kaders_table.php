<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kaders', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kader');
            $table->foreignId('posyandu_id')->constrained('posyandus')->onDelete('cascade');
            $table->string('alamat')->nullable();
            $table->string('rw', 10)->nullable();
            $table->enum('status_kehadiran', ['hadir', 'tidak_hadir'])->default('hadir');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kaders');
    }
};
