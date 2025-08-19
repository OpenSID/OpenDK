<?php

namespace Database\Factories;

use App\Models\Keluarga;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeluargaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Keluarga::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'no_kk' => $this->faker->numerify('################'), // 16 digit KK number
            'nik_kepala' => $this->faker->numerify('################'), // 16 digit NIK
            'tgl_daftar' => $this->faker->date(),
            'tgl_cetak_kk' => $this->faker->dateTimeThisYear(),
            'alamat' => $this->faker->address(),
            'dusun' => $this->faker->randomElement(['Dusun I', 'Dusun II', 'Dusun III']),
            'rt' => $this->faker->randomElement(['001', '002', '003', '004']),
            'rw' => $this->faker->randomElement(['01', '02', '03', '04']),
            'desa_id' => $this->faker->numerify('##########'), // 10 digit desa ID
        ];
    }
}
