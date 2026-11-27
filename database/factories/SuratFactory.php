<?php

namespace Database\Factories;

use App\Models\DataDesa;
use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\Surat;
use App\Enums\LogVerifikasiSurat;
use App\Enums\StatusSurat;
use App\Enums\StatusVerifikasiSurat;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuratFactory extends Factory
{
    protected $model = Surat::class;

    public function definition()
    {
        $jenisSurat = JenisSurat::inRandomOrder()->first();

        return [
            'desa_id' => function () {
                return DataDesa::firstOrCreate(['nama' => 'Desa Contoh'], [
                    'desa_id' => '3301011234567',
                    'nama' => 'Desa Contoh',
                    'website' => 'https://example.com',
                    'luas_wilayah' => 10.5,
                ])->desa_id;
            },
            'nik' => function () {
                $penduduk = Penduduk::factory()->create();
                return $penduduk->nik;
            },
            'pengurus_id' => function () {
                return \App\Models\Pengurus::factory()->create()->id;
            },
            'tanggal' => $this->faker->date(),
            'nomor' => strtoupper(Str::random(10)),
            'nama' => $jenisSurat->nama ?? $this->faker->randomElement([
                'Surat Pengantar RT/RW',
                'Fotokopi KK',
                'Surat Keterangan Kematian',
                'Surat Keterangan Cerai',
                'Surat Pindah Datang',
                'Fotokopi Ijasah Terakhir',
            ]),
            'nama_penduduk' => $this->faker->name(),
            'file' => 'dummy.pdf',
            'keterangan' => $this->faker->sentence(),
            'log_verifikasi' => LogVerifikasiSurat::Operator,
            'verifikasi_operator' => StatusVerifikasiSurat::MenungguVerifikasi,
            'verifikasi_sekretaris' => StatusVerifikasiSurat::TidakAktif,
            'verifikasi_camat' => StatusVerifikasiSurat::TidakAktif,
            'status_tte' => true,
            'status' => StatusSurat::Permohonan,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function menungguSekretaris()
    {
        return $this->state(function (array $attributes) {
            return [
                'log_verifikasi' => LogVerifikasiSurat::Sekretaris,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::MenungguVerifikasi,
            ];
        });
    }

    public function menungguCamat()
    {
        return $this->state(function (array $attributes) {
            return [
                'log_verifikasi' => LogVerifikasiSurat::Camat,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_camat' => StatusVerifikasiSurat::MenungguVerifikasi,
            ];
        });
    }

    public function menungguTTE()
    {
        return $this->state(function (array $attributes) {
            return [
                'log_verifikasi' => LogVerifikasiSurat::ProsesTTE,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_camat' => StatusVerifikasiSurat::TelahDiverifikasi,
            ];
        });
    }

    public function arsip()
    {
        return $this->state(function (array $attributes) {
            return [
                'log_verifikasi' => LogVerifikasiSurat::SudahTTE,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_camat' => StatusVerifikasiSurat::TelahDiverifikasi,
                'status' => StatusSurat::Arsip,
                'status_tte' => true,
            ];
        });
    }

    public function ditolak()
    {
        return $this->state(function (array $attributes) {
            return [
                'log_verifikasi' => LogVerifikasiSurat::Ditolak,
                'verifikasi_operator' => StatusVerifikasiSurat::TelahDiverifikasi,
                'verifikasi_sekretaris' => StatusVerifikasiSurat::TidakAktif,
                'verifikasi_camat' => StatusVerifikasiSurat::TidakAktif,
                'status' => StatusSurat::Ditolak,
                'keterangan' => 'Dokumen tidak lengkap',
            ];
        });
    }
}
