<?php

namespace Database\Factories;

use App\Models\Pembangunan;
use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembangunanFactory extends Factory
{
    protected $model = Pembangunan::class;

    public function definition()
    {
        return [
            'id' => function () {
                // Get the maximum ID from existing records and add a random increment
                // This ensures uniqueness while staying within integer range
                $maxId = Pembangunan::max('id') ?? 0;
                return $maxId + rand(1, 100);
            },
            'desa_id' => function () {
                return DataDesa::firstOrCreate(['nama' => 'Desa Contoh'], ['nama' => 'Desa Contoh', 'website' => 'https://example.com', 'luas_wilayah' => 10.5])->id;
            },
            'judul' => $this->faker->sentence(4),
            'sumber_dana' => $this->faker->randomElement(['APBD', 'APBN', 'Swadaya', 'Bantuan']),
            'anggaran' => $this->faker->randomFloat(2, 10000000, 500000000),
            'volume' => $this->faker->randomNumber(3) . ' unit',
            'tahun_anggaran' => $this->faker->numberBetween(2020, 2025),
            'pelaksana_kegiatan' => $this->faker->company,
            'lokasi' => $this->faker->address,
            'keterangan' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'status' => $this->faker->randomElement([0, 1]),
            'foto' => $this->faker->word . '.jpg',
            'perubahan_anggaran' => $this->faker->randomFloat(2, 0, 50000000),
            'sumber_biaya_pemerintah' => $this->faker->randomFloat(2, 0, 100000000),
            'sumber_biaya_provinsi' => $this->faker->randomFloat(2, 0, 50000000),
            'sumber_biaya_kab_kota' => $this->faker->randomFloat(2, 0, 50000000),
            'sumber_biaya_swadaya' => $this->faker->randomFloat(2, 0, 20000000),
            'sumber_biaya_jumlah' => $this->faker->randomFloat(2, 100000000, 300000000),
            'manfaat' => $this->faker->sentence(2),
            'waktu' => $this->faker->numberBetween(30, 365),
        ];
    }
}
