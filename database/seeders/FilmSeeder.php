<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Film;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $films = [
            [
                'judul' => 'Avatar: The Way of Water',
                'sinopsis' => 'Sekuel dari Avatar yang mengikuti keluarga Jake Sully dalam petualangan baru di planet Pandora.',
                'durasi_menit' => 192,
                'rating_usia' => 'PG-13',
                'harga_film' => 75000,
            ],
            [
                'judul' => 'Top Gun: Maverick',
                'sinopsis' => 'Pete "Maverick" Mitchell kembali sebagai pilot test yang menantang batas-batas kecepatan.',
                'durasi_menit' => 130,
                'rating_usia' => 'PG-13',
                'harga_film' => 70000,
            ],
            [
                'judul' => 'Spider-Man: No Way Home',
                'sinopsis' => 'Peter Parker menghadapi konsekuensi setelah identitasnya sebagai Spider-Man terungkap.',
                'durasi_menit' => 148,
                'rating_usia' => 'PG-13',
                'harga_film' => 80000,
            ],
            [
                'judul' => 'Black Panther: Wakanda Forever',
                'sinopsis' => 'Rakyat Wakanda berjuang untuk melindungi negara mereka setelah kematian Raja T\'Challa.',
                'durasi_menit' => 161,
                'rating_usia' => 'PG-13',
                'harga_film' => 72000,
            ],
            [
                'judul' => 'Jurassic World Dominion',
                'sinopsis' => 'Dinosaurus hidup berdampingan dengan manusia di seluruh dunia dalam petualangan epik ini.',
                'durasi_menit' => 147,
                'rating_usia' => 'PG-13',
                'harga_film' => 68000,
            ],
            [
                'judul' => 'The Batman',
                'sinopsis' => 'Bruce Wayne dalam tahun kedua sebagai Batman mengungkap korupsi di Gotham City.',
                'durasi_menit' => 176,
                'rating_usia' => 'PG-13',
                'harga_film' => 77000,
            ],
            [
                'judul' => 'Encanto',
                'sinopsis' => 'Seorang gadis dari keluarga ajaib berjuang menyelamatkan keajaiban keluarganya.',
                'durasi_menit' => 102,
                'rating_usia' => 'G',
                'harga_film' => 60000,
            ],
            [
                'judul' => 'Dune',
                'sinopsis' => 'Paul Atreides menjalani takdirnya di planet gurun Arrakis dalam epik fiksi ilmiah ini.',
                'durasi_menit' => 155,
                'rating_usia' => 'PG-13',
                'harga_film' => 73000,
            ],
        ];

        foreach ($films as $film) {
            Film::create($film);
        }
    }
}
