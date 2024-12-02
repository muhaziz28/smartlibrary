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
        Schema::create('moduls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pertemuan_id');
            $table->foreign('pertemuan_id')->references('id')->on('pertemuan')->onDelete('cascade');
            $table->string('file')->nullable();
            $table->string('link')->nullable();
            $table->enum('type', ['teori', 'praktikum'])->default('teori');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moduls');
    }
};
