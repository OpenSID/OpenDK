<?php

namespace Database\Factories;

use App\Models\AnggaranDesa;
use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnggaranDesaFactory extends Factory
{
    protected $model = AnggaranDesa::class;

    public function definition()
    {
        return [
            'desa_id' => function () {
                return DataDesa::firstOrCreate(['nama' => 'Desa Contoh'], ['desa_id' => '3301011234567', 'nama' => 'Desa Contoh', 'website' => 'https://example.com', 'luas_wilayah' => 10.5])->desa_id;
            },
            'no_akun' => $this->faker->numerify('####.##.##'),
            'nama_akun' => $this->faker->words(3, true),
            'jumlah' => $this->faker->randomFloat(2, 100000, 10000000),
            'bulan' => $this->faker->numberBetween(1, 12),
            'tahun' => $this->faker->numberBetween(2020, 2025),
        ];
    }
}
