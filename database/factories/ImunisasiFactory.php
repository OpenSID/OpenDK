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
            'desa_id' => DataDesa::factory(),
            'cakupan_imunisasi' => $this->faker->numberBetween(50, 100),
            'bulan' => $this->faker->numberBetween(1, 12),
            'tahun' => $this->faker->year,
        ];
    }
}
