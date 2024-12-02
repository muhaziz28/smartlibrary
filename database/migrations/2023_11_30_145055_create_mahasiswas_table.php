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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim')->primary();
            $table->string('nama_mahasiswa');
            $table->string('username_telegram')->nullable();
            $table->unsignedBigInteger('fakultas_id')->nullable();
            $table->foreign('fakultas_id')->references('id')->on('fakultas');
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->foreign('prodi_id')->references('id')->on('prodi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
