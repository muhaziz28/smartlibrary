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
        Schema::create('periode_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_id');
            $table->foreign('periode_id')->references('id')->on('periode');
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->foreign('mata_kuliah_id')->references('id')->on('mata_kuliah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_mata_kuliah');
    }
};
