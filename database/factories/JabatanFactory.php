<?php

namespace Database\Factories;

use App\Models\Jabatan;
use App\Enums\JenisJabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class JabatanFactory extends Factory
{
    protected $model = Jabatan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->jobTitle,
            'tupoksi' => $this->faker->sentence,
            'jenis' => $this->faker->randomElement([
                JenisJabatan::Camat,
                JenisJabatan::Sekretaris,
                JenisJabatan::JabatanLainnya
            ]),
        ];
    }

    public function camat()
    {
        return $this->state([
            'nama' => 'Camat',
            'jenis' => JenisJabatan::Camat,
        ]);
    }

    public function sekretaris()
    {
        return $this->state([
            'nama' => 'Sekretaris',
            'jenis' => JenisJabatan::Sekretaris,
        ]);
    }

    public function jabatanLainnya()
    {
        return $this->state([
            'jenis' => JenisJabatan::JabatanLainnya,
        ]);
    }
}
