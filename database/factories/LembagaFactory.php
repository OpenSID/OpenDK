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
            'lembaga_kategori_id' => function () {
                return \App\Models\KategoriLembaga::firstOrCreate(['nama' => 'Lembaga Swadaya Masyarakat'], ['nama' => 'Lembaga Swadaya Masyarakat'])->id;
            },
            'penduduk_id' => function () {
                return \App\Models\Penduduk::factory()->create()->id;
            },
            'kode' => $this->faker->unique()->numerify('LMBG###'),
            'nama' => $nama,
            'slug' => Str::slug($nama) . '-' . $this->faker->unique()->randomNumber(3),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}