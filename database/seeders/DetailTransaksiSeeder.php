<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetailTransaksi;

class DetailTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $detailTransaksis = [
            // Transaksi 1 - 3 tiket
            [
                'id_transaksi' => 1,
                'id_jadwal' => 1,
                'seat_id' => 25, // Studio 1, seat C01
                'id_studio' => 1,
                'harga' => 50000,
                'created_at' => '2025-11-29 10:30:00',
            ],
            [
                'id_transaksi' => 1,
                'id_jadwal' => 1,
                'seat_id' => 26, // Studio 1, seat C02
                'id_studio' => 1,
                'harga' => 50000,
                'created_at' => '2025-11-29 10:30:00',
            ],
            [
                'id_transaksi' => 1,
                'id_jadwal' => 1,
                'seat_id' => 27, // Studio 1, seat C03
                'id_studio' => 1,
                'harga' => 50000,
                'created_at' => '2025-11-29 10:30:00',
            ],

            // Transaksi 2 - 2 tiket
            [
                'id_transaksi' => 2,
                'id_jadwal' => 2,
                'seat_id' => 40, // Studio 1, seat D04
                'id_studio' => 1,
                'harga' => 50000,
                'created_at' => '2025-11-29 11:15:00',
            ],
            [
                'id_transaksi' => 2,
                'id_jadwal' => 2,
                'seat_id' => 41, // Studio 1, seat D05
                'id_studio' => 1,
                'harga' => 50000,
                'created_at' => '2025-11-29 11:15:00',
            ],

            // Transaksi 3 - 2 tiket VIP
            [
                'id_transaksi' => 3,
                'id_jadwal' => 14, // Studio 4 (VIP)
                'seat_id' => 433, // Studio 4, seat A01 (VIP)
                'id_studio' => 4,
                'harga' => 100000,
                'created_at' => '2025-11-29 12:00:00',
            ],
            [
                'id_transaksi' => 3,
                'id_jadwal' => 14,
                'seat_id' => 434, // Studio 4, seat A02 (VIP)
                'id_studio' => 4,
                'harga' => 100000,
                'created_at' => '2025-11-29 12:00:00',
            ],

            // Transaksi 4 - 2 tiket regular + 1 premium
            [
                'id_transaksi' => 4,
                'id_jadwal' => 18,
                'seat_id' => 580, // Studio 5, seat H04
                'id_studio' => 5,
                'harga' => 40000,
                'created_at' => '2025-11-29 13:30:00',
            ],
            [
                'id_transaksi' => 4,
                'id_jadwal' => 18,
                'seat_id' => 581, // Studio 5, seat H05
                'id_studio' => 5,
                'harga' => 40000,
                'created_at' => '2025-11-29 13:30:00',
            ],
            [
                'id_transaksi' => 4,
                'id_jadwal' => 18,
                'seat_id' => 555, // Studio 5, seat F07 (Premium)
                'id_studio' => 5,
                'harga' => 40000,
                'created_at' => '2025-11-29 13:30:00',
            ],

            // Transaksi 5 - 3 tiket
            [
                'id_transaksi' => 5,
                'id_jadwal' => 22,
                'seat_id' => 745, // Studio 7, seat E05
                'id_studio' => 7,
                'harga' => 60000,
                'created_at' => '2025-11-29 14:45:00',
            ],
            [
                'id_transaksi' => 5,
                'id_jadwal' => 22,
                'seat_id' => 746, // Studio 7, seat E06
                'id_studio' => 7,
                'harga' => 60000,
                'created_at' => '2025-11-29 14:45:00',
            ],
            [
                'id_transaksi' => 5,
                'id_jadwal' => 22,
                'seat_id' => 747, // Studio 7, seat E07
                'id_studio' => 7,
                'harga' => 60000,
                'created_at' => '2025-11-29 14:45:00',
            ],

            // Transaksi 6 - 2 tiket
            [
                'id_transaksi' => 6,
                'id_jadwal' => 24,
                'seat_id' => 885, // Studio 8, seat G05
                'id_studio' => 8,
                'harga' => 80000,
                'created_at' => '2025-11-29 16:20:00',
            ],
            [
                'id_transaksi' => 6,
                'id_jadwal' => 24,
                'seat_id' => 886, // Studio 8, seat G06
                'id_studio' => 8,
                'harga' => 80000,
                'created_at' => '2025-11-29 16:20:00',
            ],

            // Transaksi 7 - 2 tiket
            [
                'id_transaksi' => 7,
                'id_jadwal' => 27,
                'seat_id' => 1025, // Studio 9, seat F01
                'id_studio' => 9,
                'harga' => 70000,
                'created_at' => '2025-11-29 17:10:00',
            ],
            [
                'id_transaksi' => 7,
                'id_jadwal' => 27,
                'seat_id' => 1026, // Studio 9, seat F02
                'id_studio' => 9,
                'harga' => 70000,
                'created_at' => '2025-11-29 17:10:00',
            ],

            // Transaksi 8 - 3 tiket IMAX
            [
                'id_transaksi' => 8,
                'id_jadwal' => 31, // Studio 11 (IMAX)
                'seat_id' => 1305, // Studio 11, seat E05
                'id_studio' => 11,
                'harga' => 100000,
                'created_at' => '2025-11-29 18:30:00',
            ],
            [
                'id_transaksi' => 8,
                'id_jadwal' => 31,
                'seat_id' => 1306, // Studio 11, seat E06
                'id_studio' => 11,
                'harga' => 100000,
                'created_at' => '2025-11-29 18:30:00',
            ],
            [
                'id_transaksi' => 8,
                'id_jadwal' => 31,
                'seat_id' => 1307, // Studio 11, seat E07
                'id_studio' => 11,
                'harga' => 100000,
                'created_at' => '2025-11-29 18:30:00',
            ],
        ];

        foreach ($detailTransaksis as $detail) {
            DetailTransaksi::create($detail);
        }
    }
}
