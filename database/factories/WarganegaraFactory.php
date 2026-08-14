<?php

namespace Database\Factories;

use App\Models\Warganegara;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarganegaraFactory extends Factory
{
    /**
     * The name of factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warganegara::class;

    /**
     * Define model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->randomElement(['WNI', 'WNA']),
        ];
    }
}