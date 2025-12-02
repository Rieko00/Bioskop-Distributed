<?php

namespace Database\Factories;

use App\Models\SeatMap;
use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatMapFactory extends Factory
{
    protected $model = SeatMap::class;

    public function definition(): array
    {
        $baris = $this->faker->randomLetter;
        $kolom = $this->faker->numberBetween(1, 20);

        return [
            'id_studio' => Studio::factory(),
            'seat_code' => $baris . $kolom,
            'no_baris' => $baris,
            'no_kolom' => (string)$kolom,
            'tipe' => $this->faker->randomElement(['Regular', 'Premium', 'VIP']),
        ];
    }
}
