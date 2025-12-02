<?php

namespace Database\Factories;

use App\Models\Studio;
use App\Models\Cabang;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudioFactory extends Factory
{
    protected $model = Studio::class;

    public function definition(): array
    {
        return [
            'id_cabang' => Cabang::factory(),
            'nama_studio' => 'Studio ' . $this->faker->randomLetter . $this->faker->numberBetween(1, 10),
            'tipe_studio' => $this->faker->randomElement(['Regular', 'IMAX', 'Dolby Atmos', '4DX', 'VIP']),
            'kapasitas' => $this->faker->numberBetween(50, 200),
        ];
    }
}
