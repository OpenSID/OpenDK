<?php

namespace Database\Factories;

use App\Models\LaporanPenduduk;
use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanPendudukFactory extends Factory
{
    protected $model = LaporanPenduduk::class;

    public function definition()
    {
        return [
            'judul' => $this->faker->sentence(4),
            'bulan' => $this->faker->numberBetween(1, 12),
            'tahun' => $this->faker->year,
            'nama_file' => $this->faker->word . '.pdf',
            'id_laporan_penduduk' => $this->faker->unique()->numberBetween(1, 999999),
            'desa_id' => DataDesa::factory(),
            'imported_at' => $this->faker->dateTime,
        ];
    }
}
