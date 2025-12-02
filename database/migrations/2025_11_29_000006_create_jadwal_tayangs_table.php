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
        Schema::create('jadwal_tayangs', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->unsignedBigInteger('id_studio')->nullable();
            $table->unsignedBigInteger('id_film')->nullable();
            $table->date('tanggal_tayang')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->integer('harga_tiket')->nullable();
            $table->timestamps();

            $table->foreign('id_studio')->references('id_studio')->on('studios')->onDelete('cascade');
            $table->foreign('id_film')->references('id_film')->on('films')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tayangs');
    }
};
