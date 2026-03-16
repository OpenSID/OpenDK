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
            'desa_id'             => function () {
                return DataDesa::firstOrCreate(['nama' => 'Desa Contoh'], ['nama' => 'Desa Contoh', 'website' => 'https://example.com', 'luas_wilayah' => 10.5])->id;
            },
            'nik'                 => $this->faker->numerify('################'), // 16 digit char
            'pengurus_id'         => function () {
                return User::factory()->create()->id;
            },
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
