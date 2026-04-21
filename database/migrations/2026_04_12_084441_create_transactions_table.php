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
        Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('posyandu_id')->constrained()->cascadeOnDelete();
    $table->enum('jenis', ['masuk', 'keluar']);
    $table->string('kategori');
    $table->integer('jumlah');
    $table->text('keterangan')->nullable();
    $table->date('tanggal');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
