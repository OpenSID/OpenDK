<?php

namespace Database\Factories;

use App\Models\Cacat;
use Illuminate\Database\Eloquent\Factories\Factory;

class CacatFactory extends Factory
{
    protected $model = Cacat::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
        ];
    }
}