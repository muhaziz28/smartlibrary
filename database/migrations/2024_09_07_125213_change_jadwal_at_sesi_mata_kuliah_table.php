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
        Schema::table('sesi_mata_kuliah', function (Blueprint $table) {
            $table->unsignedBigInteger('jadwal_teori');
            $table->foreign('jadwal_teori')->references('id')->on('jam_perkuliahans')->onDelete('cascade');
            $table->unsignedBigInteger('jadwal_praktikum');
            $table->foreign('jadwal_praktikum')->references('id')->on('jam_perkuliahans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sesi_mata_kuliah', function (Blueprint $table) {
            $table->dropColumn('jadwal_teori');
            $table->dropColumn('jadwal_praktikum');
        });
    }
};
