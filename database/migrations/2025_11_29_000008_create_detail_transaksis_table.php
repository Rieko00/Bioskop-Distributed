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
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id('id_detail_transaksi');
            $table->unsignedBigInteger('id_transaksi')->nullable();
            $table->unsignedBigInteger('id_jadwal')->nullable();
            $table->unsignedBigInteger('seat_id')->nullable();
            $table->unsignedBigInteger('id_studio')->nullable();
            $table->integer('harga')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksis')->onDelete('cascade');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_tayangs');
            $table->foreign('seat_id')->references('seat_id')->on('seat_maps');
            $table->foreign('id_studio')->references('id_studio')->on('studios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
