<?php

namespace Database\Factories;

use App\Models\GolonganDarah;
use Illuminate\Database\Eloquent\Factories\Factory;

class GolonganDarahFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GolonganDarah::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->randomElement(['A', 'B', 'AB', 'O']),
        ];
    }
}