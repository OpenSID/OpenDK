<?php

namespace Database\Factories;

use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendudukFactory extends Factory
{
    protected $model = Penduduk::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'nik' => $this->faker->numerify('################'), // 16 digits
            'id_kk' => function () {
                return \App\Models\Keluarga::factory()->create()->id;
            },
            'kk_level' => $this->faker->numberBetween(1, 5),
            'id_rtm' => $this->faker->optional()->numberBetween(1, 999999),
            'rtm_level' => $this->faker->numberBetween(1, 5),
            'sex' => $this->faker->numberBetween(1, 2),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'agama_id' => function () {
                return \App\Models\Agama::firstOrCreate(['nama' => 'Islam'], ['nama' => 'Islam'])->id;
            },
            'pendidikan_kk_id' => function () {
                $nama = $this->faker->randomElement(['SD', 'SMP', 'SMA', 'Diploma I/II', 'Diploma III/Sarjana Muda', 'Diploma IV/Strata I', 'Strata II', 'Strata III', 'Tidak/Tidak Pernah Sekolah']);
                return \App\Models\PendidikanKK::firstOrCreate(['nama' => $nama], ['nama' => $nama])->id;
            },
            'pendidikan_id' => function () {
                $nama = $this->faker->randomElement(['Belum Tamat SD/SD', 'SD', 'SLTP/Sederajat', 'SLTA/Sederajat', 'Diploma I/II', 'Diploma III/Sarjana Muda', 'Diploma IV/Strata I', 'Strata II', 'Strata III', 'Tidak/Tidak Pernah Sekolah']);
                return \App\Models\Pendidikan::firstOrCreate(['nama' => $nama], ['nama' => $nama])->id;
            },
            'pendidikan_sedang_id' => $this->faker->numberBetween(1, 10),
            'pekerjaan_id' => function () {
                $nama = $this->faker->randomElement(['Belum/Tidak Bekerja', 'PNS', 'TNI/Polri', 'Buruh', 'Petani', 'Pedagang', 'Pengemudi', 'Pegawai Swasta']);
                return \App\Models\Pekerjaan::firstOrCreate(['nama' => $nama], ['nama' => $nama])->id;
            },
            'status_kawin' => function () {
                $nama = $this->faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']);
                return \App\Models\Kawin::firstOrCreate(['nama' => $nama], ['nama' => $nama])->id;
            },
            'warga_negara_id' => function () {
                $nama = $this->faker->randomElement(['WNI', 'WNA']);
                return \App\Models\Warganegara::firstOrCreate(['nama' => $nama], ['nama' => $nama])->id;
            },
            'dokumen_pasport' => $this->faker->optional()->bothify('?##########'),
            'dokumen_kitas' => $this->faker->optional()->bothify('?##########'),
            'ayah_nik' => $this->faker->optional()->numerify('################'),
            'ibu_nik' => $this->faker->optional()->numerify('################'),
            'nama_ayah' => $this->faker->firstNameMale() . ' ' . $this->faker->lastName(),
            'nama_ibu' => $this->faker->firstNameFemale() . ' ' . $this->faker->lastName(),
            'foto' => $this->faker->optional()->imageUrl(200, 200, 'people'),
            'golongan_darah_id' => function () {
                $nama = $this->faker->randomElement(['A', 'B', 'AB', 'O']);
                return \App\Models\GolonganDarah::firstOrCreate(['nama' => $nama], ['nama' => $nama])->id;
            },
            'id_cluster' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->numberBetween(1, 2),
            'alamat_sebelumnya' => $this->faker->optional()->address(),
            'alamat_sekarang' => $this->faker->address(),
            'status_dasar' => $this->faker->numberBetween(1, 2),
            'hamil' => $this->faker->randomElement(['0', '1']),
            'cacat_id' => function () {
                $nama = $this->faker->randomElement(['Tuna Netra', 'Tuna Daksa', 'Tuna Rungu', 'Tuna Wicara', 'Tuna Larat', 'Tuna Grahita']);
                return \App\Models\Cacat::firstOrCreate(['nama' => $nama], ['nama' => $nama])->id;
            },
            'sakit_menahun_id' => $this->faker->optional()->numberBetween(1, 10),
            'akta_lahir' => $this->faker->optional()->bothify('?###/####/#####'),
            'akta_perkawinan' => $this->faker->optional()->bothify('?###/####/#####'),
            'tanggal_perkawinan' => $this->faker->optional()->date(),
            'akta_perceraian' => $this->faker->optional()->bothify('?###/####/#####'),
            'tanggal_perceraian' => $this->faker->optional()->date(),
            'cara_kb_id' => $this->faker->optional()->numberBetween(1, 10),
            'telepon' => $this->faker->optional()->phoneNumber(),
            'tanggal_akhir_pasport' => $this->faker->optional()->date(),
            'no_kk' => $this->faker->numerify('################'),
            'no_kk_sebelumnya' => $this->faker->optional()->numerify('################'),
            'desa_id' => function () {
                return \App\Models\DataDesa::firstOrCreate(['nama' => 'Desa Contoh'], ['nama' => 'Desa Contoh', 'website' => 'https://example.com', 'luas_wilayah' => 10.5])->id;
            },            
            'created_at' => now(),
            'updated_at' => now(),
            'imported_at' => now(),
            'id_pend_desa' => $this->faker->numberBetween(1, 999),
        ];
    }
}