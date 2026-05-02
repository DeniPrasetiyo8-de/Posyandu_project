<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mothers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nik', 16)->nullable()->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['P'])->default('P');
            $table->date('tanggal_kehamilan');
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('posyandu_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mothers');
    }
};
