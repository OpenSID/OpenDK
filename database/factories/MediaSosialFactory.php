<?php

namespace Database\Factories;

use App\Models\MediaSosial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MediaSosial>
 */
class MediaSosialFactory extends Factory
{
    protected $model = MediaSosial::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'logo'   => $this->faker->imageUrl(),
            'url'    => $this->faker->imageUrl(),
            'nama'   => $this->faker->sentence(3),
            'status' => $this->faker->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
