<?php

namespace Database\Factories;

use App\Models\DataSarana;
use App\Models\DataDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataSaranaFactory extends Factory
{
    protected $model = DataSarana::class;

    public function definition()
    {
        return [
            'desa_id' => DataDesa::factory(), // otomatis buat desa baru
            'nama' => $this->faker->word,
            'jumlah' => $this->faker->numberBetween(1, 100),
            'kategori' => $this->faker->randomElement([
                'puskesmas','puskesmas_pembantu','posyandu','pondok_bersalin',
                'paud','sd','smp','sma',
                'masjid_besar','mushola','gereja','pasar','balai_pertemuan'
            ]),
            'keterangan' => $this->faker->sentence,
        ];
    }
}
