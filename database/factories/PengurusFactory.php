<?php

namespace Database\Factories;

use App\Models\Agama;
use App\Models\Pengurus;
use App\Models\Pendidikan;
use App\Enums\JenisJabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengurus>
 */
class PengurusFactory extends Factory
{
    protected $model = Pengurus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->name(),
            'gelar_depan' => null,
            'gelar_belakang' => null,
            'nip' => random_int(100000000, 999999999),
            'nik' => random_int(1000000000000000, 9999999999999999),
            'status' => $this->faker->randomElement([0, 1]),
            'foto' => null,
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'sex' => $this->faker->randomElement([1, 2]),
            'pendidikan_id' => $this->faker->randomElement(Pendidikan::pluck('id')->toArray()),
            'agama_id' => $this->faker->randomElement(Agama::pluck('id')->toArray()),
            'no_sk' => null,
            'tanggal_sk' => $this->faker->date(),
            'masa_jabatan' => 5,
            'pangkat' => 'Camat',
            'no_henti' => null,
            'tanggal_henti' => null,
            'jabatan_id' => JenisJabatan::Camat,
        ];
    }
}
