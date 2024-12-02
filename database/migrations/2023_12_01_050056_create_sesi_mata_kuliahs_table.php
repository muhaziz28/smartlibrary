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
        Schema::create('sesi_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_mata_kuliah_id');
            $table->foreign('periode_mata_kuliah_id')->references('id')->on('periode_mata_kuliah');
            $table->string('kode_sesi', 20);
            $table->string('kode_dosen', 20);
            $table->foreign('kode_dosen')->references('kode_dosen')->on('dosen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_mata_kuliah');
    }
};
