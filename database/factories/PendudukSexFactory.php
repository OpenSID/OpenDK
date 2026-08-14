<?php

namespace Database\Factories;

use App\Models\PendudukSex;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendudukSexFactory extends Factory
{
    /**
     * The name of factory's corresponding model.
     *
     * @var string
     */
    protected $model = PendudukSex::class;

    /**
     * Define model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->randomElement(['Laki-laki', 'Perempuan']),
        ];
    }
}