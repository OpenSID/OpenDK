<?php

namespace Database\Factories;

use App\Models\KategoriLembaga;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriLembagaFactory extends Factory
{
    protected $model = KategoriLembaga::class;

    public function definition()
    {
        return [
            'nama' => $this->faker->words(2, true),
            'deskripsi' => $this->faker->sentence,
        ];
    }
}