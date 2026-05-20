<?php

namespace Database\Factories;

use App\Models\Artikel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artikell>
 */
class ArtikelFactory extends Factory
{
    protected $model = Artikel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(),
            'gambar' => '/img/no-image.png',
            'isi' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement([0, 1]),
            'tanggal_terbit' => $this->faker->date('Y-m-d'),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
