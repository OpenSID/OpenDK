<?php

namespace Database\Factories;

use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendudukFactory extends Factory
{
    protected $model = Penduduk::class;

    public function definition()
    {
        return [            
            'desa_id' => 1,
            'nama' => $this->faker->name,
            'nik' => $this->faker->unique()->numerify('################'),
            'no_kk' => $this->faker->numerify('################'),
            'alamat' => $this->faker->address,
            'pendidikan_kk_id' => 1,
            'tanggal_lahir' => $this->faker->date(),            
            'pekerjaan_id' => 1,
            'status_kawin' => 1,
            'sex' => 1,
            'status_dasar' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}