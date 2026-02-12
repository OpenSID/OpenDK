<?php

namespace Database\Factories;

use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataDesaFactory extends Factory
{
    protected $model = DataDesa::class;

    public function definition(): array
    {
        return [
            'desa_id' => $this->faker->numerify('D%06d'),
            'nama' => $this->faker->city(),
            'website' => $this->faker->url(),
            'luas_wilayah' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}