<?php

namespace Database\Factories;

use App\Models\ArtikelKategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArtikelKategori>
 */
class ArtikelKategoriFactory extends Factory
{
    protected $model = ArtikelKategori::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $nama = $this->faker->unique()->words(2, true);
        
        return [
            'nama_kategori' => ucfirst($nama),
            'slug' => \Illuminate\Support\Str::slug($nama),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}