<?php

namespace Database\Factories;

use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfilFactory extends Factory
{
    protected $model = Profil::class;

    public function definition(): array
    {
        return [
            'provinsi_id' => $this->faker->randomElement(['31', '32', '33', '34', '35', '36', '51', '52', '53', '61', '62', '63', '64', '65', '71', '72', '73', '74', '75', '76', '81', '82', '91', '92']),
            'kabupaten_id' => $this->faker->numerify('####'),
            'kecamatan_id' => $this->faker->numerify('#######'),
            'alamat' => substr($this->faker->address(), 0, 200), // Limit to 200 chars
            'kode_pos' => substr($this->faker->postcode(), 0, 12), // Limit to 12 chars
            'telepon' => substr($this->faker->phoneNumber(), 0, 15), // Limit to 15 chars
            'email' => $this->faker->unique()->safeEmail(),
            'tahun_pembentukan' => $this->faker->numberBetween(1900, date('Y')),
            'dasar_pembentukan' => substr($this->faker->sentence(), 0, 20), // Limit to 20 chars
            'nama_camat' => substr($this->faker->name(), 0, 150), // Limit to 150 chars
            'sekretaris_camat' => substr($this->faker->name(), 0, 150), // Limit to 150 chars
            'kepsek_pemerintahan_umum' => substr($this->faker->name(), 0, 150), // Limit to 150 chars
            'kepsek_kesejahteraan_masyarakat' => substr($this->faker->name(), 0, 150), // Limit to 150 chars
            'kepsek_pemberdayaan_masyarakat' => substr($this->faker->name(), 0, 150), // Limit to 150 chars
            'kepsek_pelayanan_umum' => substr($this->faker->name(), 0, 150), // Limit to 150 chars
            'kepsek_trantib' => substr($this->faker->name(), 0, 150), // Limit to 150 chars
            'file_struktur_organisasi' => substr($this->faker->word(), 0, 255) . '.pdf', // Limit to 255 chars
            'file_logo' => substr($this->faker->word(), 0, 255) . '.png', // Limit to 255 chars
            'visi' => $this->faker->text(500), // Use text instead of paragraph to control length
            'misi' => $this->faker->text(500), // Use text instead of paragraph to control length
        ];
    }
}