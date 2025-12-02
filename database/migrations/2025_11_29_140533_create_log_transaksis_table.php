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
        Schema::create('log_transaksis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Kolom foreign key harus didefinisikan dulu
            $table->unsignedBigInteger('id_cabang')->nullable();
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->unsignedBigInteger('id_studio')->nullable();
            $table->unsignedBigInteger('id_film')->nullable();
            $table->unsignedBigInteger('id_jadwal')->nullable();
            $table->unsignedBigInteger('seat_id')->nullable();
            $table->unsignedBigInteger('id_transaksi')->nullable();
            $table->unsignedBigInteger('id_detail_transaksi')->nullable();

            // Data transaksi
            $table->integer('total_bayar')->nullable();
            $table->timestamp('waktu_transaksi')->nullable();

            // Foreign key constraints
            $table->foreign('id_cabang')->references('id_cabang')->on('cabangs');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans');
            $table->foreign('id_studio')->references('id_studio')->on('studios');
            $table->foreign('id_film')->references('id_film')->on('films');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_tayangs');
            $table->foreign('seat_id')->references('seat_id')->on('seat_maps');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksis');
            $table->foreign('id_detail_transaksi')->references('id_detail_transaksi')->on('detail_transaksis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_transaksis');
    }
};
