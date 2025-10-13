<?php

namespace Database\Factories;

use App\Models\Pesan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesanFactory extends Factory
{
    protected $model = Pesan::class;

    public function definition()
    {
        return [
            'judul' => $this->faker->sentence(3),
            'das_data_desa_id' => $this->faker->unique()->numerify('##########'), // Kode desa sebagai string
            'jenis' => $this->faker->randomElement([Pesan::PESAN_MASUK, Pesan::PESAN_KELUAR]),
            'sudah_dibaca' => $this->faker->randomElement([Pesan::BELUM_DIBACA, Pesan::SUDAH_DIBACA]),
            'created_at' => $this->faker->dateTime,
        ];
    }
}