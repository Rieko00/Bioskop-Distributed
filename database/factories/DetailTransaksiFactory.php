<?php

namespace Database\Factories;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\JadwalTayang;
use App\Models\SeatMap;
use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailTransaksiFactory extends Factory
{
    protected $model = DetailTransaksi::class;

    public function definition(): array
    {
        return [
            'id_transaksi' => Transaksi::factory(),
            'id_jadwal' => JadwalTayang::factory(),
            'seat_id' => SeatMap::factory(),
            'id_studio' => Studio::factory(),
            'harga' => $this->faker->numberBetween(25000, 100000),
        ];
    }
}
