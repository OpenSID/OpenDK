<?php

namespace Database\Factories;

use App\Models\DataUmum;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataUmumFactory extends Factory
{
    protected $model = DataUmum::class;

    public function definition(): array
    {
        return [
            'profil_id' => null, // Will be set by relationship
            'tipologi' => $this->faker->word(),
            'ketinggian' => $this->faker->numberBetween(0, 5000),
            'luas_wilayah' => $this->faker->randomFloat(2, 1, 1000),
            'jumlah_penduduk' => $this->faker->numberBetween(1000, 100000),
            'jml_laki_laki' => $this->faker->numberBetween(500, 50000),
            'jml_perempuan' => $this->faker->numberBetween(500, 50000),
            'bts_wil_utara' => $this->faker->sentence(),
            'bts_wil_timur' => $this->faker->sentence(),
            'bts_wil_selatan' => $this->faker->sentence(),
            'bts_wil_barat' => $this->faker->sentence(),
            'jml_puskesmas' => $this->faker->numberBetween(0, 10),
            'jml_puskesmas_pembantu' => $this->faker->numberBetween(0, 20),
            'jml_posyandu' => $this->faker->numberBetween(0, 50),
            'jml_pondok_bersalin' => $this->faker->numberBetween(0, 10),
            'jml_paud' => $this->faker->numberBetween(0, 50),
            'jml_sd' => $this->faker->numberBetween(0, 100),
            'jml_smp' => $this->faker->numberBetween(0, 50),
            'jml_sma' => $this->faker->numberBetween(0, 30),
            'jml_masjid_besar' => $this->faker->numberBetween(0, 20),
            'jml_mushola' => $this->faker->numberBetween(0, 100),
            'jml_gereja' => $this->faker->numberBetween(0, 10),
            'jml_pasar' => $this->faker->numberBetween(0, 10),
            'jml_balai_pertemuan' => $this->faker->numberBetween(0, 20),
            'embed_peta' => $this->faker->text(500),
        ];
    }
}