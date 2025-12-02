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
        Schema::create('seat_maps', function (Blueprint $table) {
            $table->id('seat_id');
            $table->unsignedBigInteger('id_studio')->nullable();
            $table->string('seat_code', 5)->nullable();
            $table->string('no_baris', 4)->nullable();
            $table->string('no_kolom', 4)->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('id_studio')->references('id_studio')->on('studios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_maps');
    }
};
