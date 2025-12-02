<?php

namespace Database\Factories;

use App\Models\JadwalTayang;
use App\Models\Studio;
use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalTayangFactory extends Factory
{
    protected $model = JadwalTayang::class;

    public function definition(): array
    {
        return [
            'id_studio' => Studio::factory(),
            'id_film' => Film::factory(),
            'waktu_mulai' => $this->faker->time(),
        ];
    }
}
