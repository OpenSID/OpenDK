<?php

namespace Database\Factories;

use App\Models\Kawin;
use Illuminate\Database\Eloquent\Factories\Factory;

class KawinFactory extends Factory
{
    /**
     * The name of factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kawin::class;

    /**
     * Define model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->randomElement(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']),
        ];
    }
}