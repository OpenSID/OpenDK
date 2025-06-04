<?php

namespace Database\Factories;

use App\Models\LembagaAnggota;
use App\Models\Lembaga;
use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Factories\Factory;

class LembagaAnggotaFactory extends Factory
{
    protected $model = LembagaAnggota::class;

    public function definition()
    {
        return [
            'lembaga_id' => Lembaga::factory(),
            'penduduk_id' => Penduduk::factory(),
            'no_anggota' => $this->faker->unique()->numerify('ANGG###'),
            'jabatan' => $this->faker->numberBetween(1,5), // Assuming Jabatan factory exists
            'no_sk_jabatan' => $this->faker->bothify('SKJ-####'),
            'no_sk_pengangkatan' => $this->faker->bothify('SKP-####'),
            'tgl_sk_pengangkatan' => $this->faker->date(),
            'no_sk_pemberhentian' => null,
            'tgl_sk_pemberhentian' => null,
            'periode' => $this->faker->year . '-' . ($this->faker->year + 1),
            'keterangan' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}