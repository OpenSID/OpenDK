<?php

namespace Database\Factories;

use App\Models\LaporanApbdes;
use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanApbdesFactory extends Factory
{
    protected $model = LaporanApbdes::class;

    public function definition()
    {
        return [
            'judul' => $this->faker->sentence(4),
            'tahun' => $this->faker->numberBetween(2020, 2025),
            'semester' => $this->faker->numberBetween(1, 2),
            'nama_file' => $this->faker->word . '.pdf',
            'desa_id' => function () {
                return DataDesa::factory()->create()->desa_id;
            },
            'imported_at' => $this->faker->dateTime(),
        ];
    }
}
