<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add trimester_status column to mothers table (for storing JSON trimester data)
        Schema::table('mothers', function (Blueprint $table) {
            if (!Schema::hasColumn('mothers', 'trimester_status')) {
                $table->json('trimester_status')->nullable()->after('berat_badan');
            }
        });
    }

    public function down()
    {
        Schema::table('mothers', function (Blueprint $table) {
            $table->dropColumn(['trimester_status']);
        });
    }
};
