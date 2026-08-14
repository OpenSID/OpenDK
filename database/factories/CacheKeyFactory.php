<?php

namespace Database\Factories;

use App\Models\CacheKey;
use Illuminate\Database\Eloquent\Factories\Factory;

class CacheKeyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CacheKey::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => $this->faker->unique()->word,
            'prefix' => $this->faker->word,
            'group' => $this->faker->optional()->word,
        ];
    }
}