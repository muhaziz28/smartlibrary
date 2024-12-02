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
        Schema::create('absensi_types', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['luring', 'daring'])->default('luring');
            $table->unsignedBigInteger('pertemuan_id');
            $table->foreign('pertemuan_id')->references('id')->on('pertemuan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_types');
    }
};
