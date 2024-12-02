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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('hadir')->default(false);
            $table->timestamp('date_in');
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
        Schema::dropIfExists('absensis');
    }
};
