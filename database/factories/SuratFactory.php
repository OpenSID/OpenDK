<?php

namespace Database\Factories;

use App\Models\DataDesa;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SuratFactory extends Factory
{
    protected $model = Surat::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'desa_id'             => DataDesa::inRandomOrder()->value('desa_id') ?? '51.02.02.2003',  // 13 digit char
            'nik'                 => $this->faker->numerify('################'), // 16 digit char
            'pengurus_id'         => User::inRandomOrder()->value('id') ?? 1,
            'tanggal'             => $this->faker->date(),
            'nomor'               => strtoupper(Str::random(10)),
            'nama'                => $this->faker->name(),
            'file'                => 'dummy.pdf',
            'keterangan'          => $this->faker->sentence(),
            'log_verifikasi'      => $this->faker->numberBetween(0, 3), // sesuaikan enum
            'verifikasi_operator' => $this->faker->numberBetween(0, 3), // angka, bukan tanggal
            'verifikasi_sekretaris' => $this->faker->numberBetween(0, 3), // angka
            'verifikasi_camat'    => $this->faker->numberBetween(0, 3), // angka
            'status_tte'          => $this->faker->boolean(),
            'status'              => $this->faker->numberBetween(0, 3), // angka
            'created_at'          => now(),
            'updated_at'          => now(),
        ];

    }
}
