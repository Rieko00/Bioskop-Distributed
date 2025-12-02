<?php

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;

class FilmFactory extends Factory
{
    protected $model = Film::class;

    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(3),
            'sinopsis' => $this->faker->paragraph(5),
            'durasi_menit' => $this->faker->numberBetween(90, 180),
            'rating_usia' => $this->faker->randomElement(['G', 'PG', 'PG-13', 'R', '17+']),
        ];
    }
}
