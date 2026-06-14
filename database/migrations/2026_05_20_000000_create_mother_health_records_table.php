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
        Schema::create('mother_health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('mothers')->onDelete('cascade');
            $table->tinyInteger('bulan_ke')->unsigned(); // 1-9 (bulan ke-)
            $table->decimal('berat_badan', 4, 1)->nullable(); // kg - nilai absolut
            $table->decimal('lila', 4, 1)->nullable(); // cm
            $table->string('tekanan_darah', 10)->nullable(); // format "120/80"
            $table->text('catatan')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('recorded_at')->nullable();
            $table->timestamps();

            // Unique constraint: 1 record per mother per bulan
            $table->unique(['mother_id', 'bulan_ke']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mother_health_records');
    }
};
