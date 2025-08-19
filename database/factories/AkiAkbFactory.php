<?php

namespace Database\Factories;

use App\Models\AkiAkb;
use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class AkiAkbFactory extends Factory
{
    protected $model = AkiAkb::class;

    public function definition()
    {
        return [
            'desa_id' => DataDesa::factory(),
            'aki' => $this->faker->numberBetween(0, 10),
            'akb' => $this->faker->numberBetween(0, 15),
            'bulan' => $this->faker->numberBetween(1, 12),
            'tahun' => $this->faker->year,
        ];
    }
}
