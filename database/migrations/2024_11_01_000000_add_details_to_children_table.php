<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            $table->decimal('berat_badan', 5, 2)->nullable()->after('jenis_kelamin');
            $table->decimal('tinggi_badan', 5, 2)->nullable()->after('berat_badan');
            $table->string('foto')->nullable()->after('tinggi_badan');
        });
    }

    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'berat_badan', 'tinggi_badan', 'foto']);
        });
    }
};

