<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaksi;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaksis = [
            [
                'id_pelanggan' => 1,
                'id_cabang' => 1,
                'waktu_transaksi' => '2025-11-29 10:30:00',
                'metode_pembayaran' => 'Credit Card',
                'total_bayar' => 150000,
            ],
            [
                'id_pelanggan' => 2,
                'id_cabang' => 1,
                'waktu_transaksi' => '2025-11-29 11:15:00',
                'metode_pembayaran' => 'Cash',
                'total_bayar' => 100000,
            ],
            [
                'id_pelanggan' => 3,
                'id_cabang' => 2,
                'waktu_transaksi' => '2025-11-29 12:00:00',
                'metode_pembayaran' => 'E-Wallet',
                'total_bayar' => 200000,
            ],
            [
                'id_pelanggan' => 4,
                'id_cabang' => 2,
                'waktu_transaksi' => '2025-11-29 13:30:00',
                'metode_pembayaran' => 'Debit Card',
                'total_bayar' => 120000,
            ],
            [
                'id_pelanggan' => 5,
                'id_cabang' => 3,
                'waktu_transaksi' => '2025-11-29 14:45:00',
                'metode_pembayaran' => 'Credit Card',
                'total_bayar' => 180000,
            ],
            [
                'id_pelanggan' => 6,
                'id_cabang' => 3,
                'waktu_transaksi' => '2025-11-29 16:20:00',
                'metode_pembayaran' => 'Bank Transfer',
                'total_bayar' => 160000,
            ],
            [
                'id_pelanggan' => 7,
                'id_cabang' => 4,
                'waktu_transaksi' => '2025-11-29 17:10:00',
                'metode_pembayaran' => 'E-Wallet',
                'total_bayar' => 140000,
            ],
            [
                'id_pelanggan' => 8,
                'id_cabang' => 5,
                'waktu_transaksi' => '2025-11-29 18:30:00',
                'metode_pembayaran' => 'Cash',
                'total_bayar' => 300000,
            ],
        ];

        foreach ($transaksis as $transaksi) {
            Transaksi::create($transaksi);
        }
    }
}
