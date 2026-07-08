<?php

namespace Database\Factories;

use App\Models\Imunisasi;
use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImunisasiFactory extends Factory
{
    protected $model = Imunisasi::class;

    public function definition()
    {
        return [
            'desa_id' => function () {
                return DataDesa::firstOrCreate(['nama' => 'Desa Contoh'], ['desa_id' => '3301011234567', 'nama' => 'Desa Contoh', 'website' => 'https://example.com', 'luas_wilayah' => 10.5])->desa_id;
            },
            'cakupan_imunisasi' => $this->faker->numberBetween(50, 100),
            'bulan' => $this->faker->numberBetween(1, 12),
            'tahun' => $this->faker->year,
        ];
    }
}
