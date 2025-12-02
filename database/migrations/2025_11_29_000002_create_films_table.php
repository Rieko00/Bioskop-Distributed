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
        Schema::create('films', function (Blueprint $table) {
            $table->id('id_film');
            $table->string('judul', 100)->nullable();
            $table->text('sinopsis')->nullable();
            $table->integer('durasi_menit')->nullable();
            $table->string('rating_usia', 5)->nullable();
            $table->integer('harga_film')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
