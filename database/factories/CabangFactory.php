<?php

namespace Database\Factories;

use App\Models\Cabang;
use Illuminate\Database\Eloquent\Factories\Factory;

class CabangFactory extends Factory
{
    protected $model = Cabang::class;

    public function definition(): array
    {
        return [
            'nama_cabang' => $this->faker->company . ' Cinema',
            'alamat' => $this->faker->address,
        ];
    }
}
