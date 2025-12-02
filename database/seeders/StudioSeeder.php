<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Studio;

class StudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = [
            // Central Plaza Studios
            ['id_cabang' => 1, 'nama_studio' => 'Studio 1', 'kapasitas' => 120],
            ['id_cabang' => 1, 'nama_studio' => 'Studio 2', 'kapasitas' => 120],
            ['id_cabang' => 1, 'nama_studio' => 'Studio 3', 'kapasitas' => 120],

            // Mall Kelapa Gading Studios
            ['id_cabang' => 2, 'nama_studio' => 'Studio A', 'kapasitas' => 120],
            ['id_cabang' => 2, 'nama_studio' => 'Studio B', 'kapasitas' => 120],
            ['id_cabang' => 2, 'nama_studio' => 'Studio C', 'kapasitas' => 120],

            // Plaza Indonesia Studios
            ['id_cabang' => 3, 'nama_studio' => 'Cinema 1', 'kapasitas' => 120],
            ['id_cabang' => 3, 'nama_studio' => 'Cinema 2', 'kapasitas' => 120],

            // Pondok Indah Mall Studios
            ['id_cabang' => 4, 'nama_studio' => 'Theatre 1', 'kapasitas' => 120],
            ['id_cabang' => 4, 'nama_studio' => 'Theatre 2', 'kapasitas' => 120],

            // Grand Indonesia Studios
            ['id_cabang' => 5, 'nama_studio' => 'Hall 1', 'kapasitas' => 120],
            ['id_cabang' => 5, 'nama_studio' => 'Hall 2', 'kapasitas' => 120],
        ];

        foreach ($studios as $studio) {
            Studio::create($studio);
        }
    }
}
