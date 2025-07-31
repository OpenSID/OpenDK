<?php

namespace Database\Factories;

use App\Models\SinergiProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class SinergiProgramFactory extends Factory
{
    protected $model = SinergiProgram::class;

    public function definition()
    {
        return [
            'gambar' => $this->faker->imageUrl(), // jika ingin dummy url
            'url' => $this->faker->url,
            'nama' => $this->faker->words(2, true),
            'status' => $this->faker->randomElement([0, 1]),
            'urutan' => $this->faker->numberBetween(1, 10),
        ];
    }
}