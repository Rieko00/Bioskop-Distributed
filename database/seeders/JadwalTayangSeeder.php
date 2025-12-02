<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JadwalTayang;

class JadwalTayangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwals = [
            // Studio 1 - Central Plaza
            ['id_studio' => 1, 'id_film' => 1, 'waktu_mulai' => '09:00:00'],
            ['id_studio' => 1, 'id_film' => 2, 'waktu_mulai' => '12:30:00'],
            ['id_studio' => 1, 'id_film' => 3, 'waktu_mulai' => '15:45:00'],
            ['id_studio' => 1, 'id_film' => 4, 'waktu_mulai' => '19:00:00'],
            ['id_studio' => 1, 'id_film' => 5, 'waktu_mulai' => '21:30:00'],

            // Studio 2 - Central Plaza (IMAX)
            ['id_studio' => 2, 'id_film' => 1, 'waktu_mulai' => '10:00:00'],
            ['id_studio' => 2, 'id_film' => 6, 'waktu_mulai' => '13:30:00'],
            ['id_studio' => 2, 'id_film' => 8, 'waktu_mulai' => '17:00:00'],
            ['id_studio' => 2, 'id_film' => 4, 'waktu_mulai' => '20:30:00'],

            // Studio 3 - Central Plaza
            ['id_studio' => 3, 'id_film' => 7, 'waktu_mulai' => '10:30:00'],
            ['id_studio' => 3, 'id_film' => 2, 'waktu_mulai' => '13:00:00'],
            ['id_studio' => 3, 'id_film' => 5, 'waktu_mulai' => '16:15:00'],
            ['id_studio' => 3, 'id_film' => 3, 'waktu_mulai' => '19:30:00'],

            // Studio 4 - Mall Kelapa Gading (VIP)
            ['id_studio' => 4, 'id_film' => 6, 'waktu_mulai' => '11:00:00'],
            ['id_studio' => 4, 'id_film' => 8, 'waktu_mulai' => '14:30:00'],
            ['id_studio' => 4, 'id_film' => 1, 'waktu_mulai' => '18:00:00'],
            ['id_studio' => 4, 'id_film' => 4, 'waktu_mulai' => '21:15:00'],

            // Studio 5 - Mall Kelapa Gading
            ['id_studio' => 5, 'id_film' => 2, 'waktu_mulai' => '09:30:00'],
            ['id_studio' => 5, 'id_film' => 3, 'waktu_mulai' => '12:45:00'],
            ['id_studio' => 5, 'id_film' => 5, 'waktu_mulai' => '16:00:00'],
            ['id_studio' => 5, 'id_film' => 7, 'waktu_mulai' => '19:15:00'],

            // Continue for other studios...
            ['id_studio' => 6, 'id_film' => 1, 'waktu_mulai' => '10:15:00'],
            ['id_studio' => 6, 'id_film' => 8, 'waktu_mulai' => '14:00:00'],
            ['id_studio' => 7, 'id_film' => 6, 'waktu_mulai' => '11:30:00'],
            ['id_studio' => 7, 'id_film' => 4, 'waktu_mulai' => '15:45:00'],
            ['id_studio' => 8, 'id_film' => 2, 'waktu_mulai' => '13:15:00'],
            ['id_studio' => 8, 'id_film' => 5, 'waktu_mulai' => '17:30:00'],
            ['id_studio' => 9, 'id_film' => 3, 'waktu_mulai' => '12:00:00'],
            ['id_studio' => 9, 'id_film' => 7, 'waktu_mulai' => '16:30:00'],
            ['id_studio' => 10, 'id_film' => 1, 'waktu_mulai' => '14:15:00'],
            ['id_studio' => 10, 'id_film' => 8, 'waktu_mulai' => '18:30:00'],
            ['id_studio' => 11, 'id_film' => 6, 'waktu_mulai' => '10:45:00'],
            ['id_studio' => 11, 'id_film' => 4, 'waktu_mulai' => '15:15:00'],
            ['id_studio' => 12, 'id_film' => 2, 'waktu_mulai' => '13:45:00'],
            ['id_studio' => 12, 'id_film' => 5, 'waktu_mulai' => '17:15:00'],
        ];

        foreach ($jadwals as $jadwal) {
            JadwalTayang::create($jadwal);
        }
    }
}
