<?php

namespace Database\Factories;

use App\Models\Lembaga;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LembagaFactory extends Factory
{
    protected $model = Lembaga::class;

    public function definition()
    {
        $nama = $this->faker->company;
        return [
            'lembaga_kategori_id' => 1, // sesuaikan jika ada factory kategori
            'penduduk_id' => 1, // sesuaikan jika ada factory penduduk
            'kode' => $this->faker->unique()->numerify('LMBG###'),
            'nama' => $nama,
            'slug' => Str::slug($nama) . '-' . $this->faker->unique()->randomNumber(3),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}