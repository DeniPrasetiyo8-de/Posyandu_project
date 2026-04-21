<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('name');
            $table->text('address')->nullable()->after('phone');
            $table->string('rt')->nullable()->after('address');
            $table->enum('rw', ['1', '2', '3', '4', '5', '6'])->nullable()->after('rt');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'rt', 'rw']);
        });
    }
};

