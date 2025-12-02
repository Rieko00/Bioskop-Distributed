<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Cabang;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition(): array
    {
        return [
            'id_pelanggan' => Pelanggan::factory(),
            'id_cabang' => Cabang::factory(),
            'waktu_transaksi' => $this->faker->dateTimeThisMonth(),
            'metode_pembayaran' => $this->faker->randomElement(['Cash', 'Credit Card', 'Debit Card', 'E-Wallet', 'Bank Transfer']),
            'total_bayar' => $this->faker->numberBetween(50000, 500000),
        ];
    }
}
