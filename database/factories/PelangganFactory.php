<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelangganFactory extends Factory
{
    protected $model = Pelanggan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'telp' => $this->faker->phoneNumber,
        ];
    }
}
