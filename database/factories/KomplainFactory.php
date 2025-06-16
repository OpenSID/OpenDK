<?php

namespace Database\Factories;

use App\Models\Komplain;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KomplainFactory extends Factory
{
    protected $model = Komplain::class;

    public function definition()
    {
        $judul = $this->faker->sentence(3);
        return [
            'komplain_id' => Komplain::generateID(), // sesuaikan jika ingin relasi dengan factory Lembaga
            'kategori' => 1, // sesuaikan jika ingin relasi dengan factory KategoriKomplain
            'nik' => $this->faker->unique()->numerify('3276############'),
            'nama' => $this->faker->name,
            'judul' => $judul,
            'slug' => Str::slug($judul) . '-' . $this->faker->unique()->randomNumber(3),
            'laporan' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['baru', 'proses', 'selesai']),
            'lampiran1' => null,
            'lampiran2' => null,
            'lampiran3' => null,
            'lampiran4' => null,
            'anonim' => $this->faker->boolean,
            'dilihat' => 0,
        ];
    }
}