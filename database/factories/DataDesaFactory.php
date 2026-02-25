<?php

namespace Database\Factories;

use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataDesaFactory extends Factory
{
    protected $model = DataDesa::class;

    public function definition()
    {
        return [
            'desa_id' => '330101'.fake()->unique()->numerify('######'), // 13 digit: kecamatan_id + 7 digits
            'nama' => $this->faker->city,
            'sebutan_desa' => $this->faker->randomElement(['Desa', 'Kelurahan']),
            'website' => $this->faker->url,
            'luas_wilayah' => $this->faker->randomFloat(2, 10, 1000),
            'path' => null,
        ];
    }
}