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
        Schema::create('pengantar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sesi_mata_kuliah_id');
            $table->foreign('sesi_mata_kuliah_id')->references('id')->on('sesi_mata_kuliah')->onDelete('cascade');
            $table->text('pengantar')->nullable();
            $table->string('file')->nullable();
            $table->string('link')->nullable();
            $table->string('video')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengantar');
    }
};
