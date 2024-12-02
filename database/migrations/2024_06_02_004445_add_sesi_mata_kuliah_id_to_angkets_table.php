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
        Schema::table('angkets', function (Blueprint $table) {
            $table->unsignedBigInteger('sesi_mata_kuliah_id');
            $table->foreign('sesi_mata_kuliah_id')->references('id')->on('sesi_mata_kuliah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angkets', function (Blueprint $table) {
            $table->dropForeign(['sesi_mata_kuliah_id']);
            $table->dropColumn('sesi_mata_kuliah_id');
        });
    }
};
