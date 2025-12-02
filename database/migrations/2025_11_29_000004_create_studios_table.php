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
        Schema::create('studios', function (Blueprint $table) {
            $table->id('id_studio');
            $table->unsignedBigInteger('id_cabang')->nullable();
            $table->string('nama_studio', 50)->nullable();
            $table->string('tipe_studio', 50)->nullable();
            $table->integer('kapasitas')->nullable();
            $table->timestamps();

            $table->foreign('id_cabang')->references('id_cabang')->on('cabangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
