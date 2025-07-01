<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    protected $model = Album::class;

    public function definition()
    {
        return [
            'judul' => $this->faker->sentence(3), // Random judul            
            'gambar' => $this->faker->imageUrl(), // Random image URL
            'status' => $this->faker->boolean(), // Random status (0 or 1)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
