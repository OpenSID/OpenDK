<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KategoriFactory extends Factory
{
    protected $model = Kategori::class;

    public function definition()
    {
        $nama = $this->faker->words(2, true);
        return [
            'nama' => $nama,
            'slug' => Str::slug($nama) . '-' . $this->faker->unique()->randomNumber(3),
        ];
    }
}