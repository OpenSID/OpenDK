<?php

namespace Database\Factories;

use App\Models\Agama;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgamaFactory extends Factory
{
    protected $model = Agama::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
        ];
    }
}