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
        Schema::create('tugas_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas');
            $table->string('nim', 10);
            $table->string('file')->nullable();
            $table->string('link')->nullable();

            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');

            $table->text('komentar')->nullable();

            $table->integer('nilai')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_mahasiswas');
    }
};
