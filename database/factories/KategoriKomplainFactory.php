<?php

namespace Database\Factories;

use App\Models\KategoriKomplain;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriKomplainFactory extends Factory
{
    protected $model = KategoriKomplain::class;

    public function definition()
    {
        return [
            'nama' => $this->faker->words(2, true),
        ];
    }
}