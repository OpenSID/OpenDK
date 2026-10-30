<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Konsolidasi database gabungan Premium menggeser id baris desa sebesar `slot x 100.000.000.000`.
 * Kolom id yang menyimpan id baris desa (atau id penduduk dari API Database Gabungan) harus BIGINT,
 * kalau tidak dorongan data desa yang sudah pindah gagal dengan "Out of range".
 * Tanda (signed/unsigned), NOT NULL, dan indeks dipertahankan; tidak ada FK yang mengacu ke kolom ini.
 */
return new class extends Migration
{
    /** tabel => [kolom => definisi baru] */
    private const KOLOM = [
        'das_penduduk' => ['id_pend_desa' => 'BIGINT NULL'],
        'das_pembangunan' => ['id' => 'BIGINT NOT NULL'],
        'das_pembangunan_dokumentasi' => ['id' => 'BIGINT NOT NULL', 'id_pembangunan' => 'BIGINT NOT NULL'],
        'das_program' => ['id' => 'BIGINT UNSIGNED NOT NULL'],
        'das_peserta_program' => ['program_id' => 'BIGINT NOT NULL'],
        'das_lembaga' => ['penduduk_id_gabungan' => 'BIGINT UNSIGNED NULL'],
        'das_lembaga_anggota' => ['penduduk_id_gabungan' => 'BIGINT UNSIGNED NULL'],
    ];

    /** Tipe sebelum pelebaran, untuk down(). */
    private const KOLOM_LAMA = [
        'das_penduduk' => ['id_pend_desa' => 'INT NULL'],
        'das_pembangunan' => ['id' => 'INT NOT NULL'],
        'das_pembangunan_dokumentasi' => ['id' => 'INT NOT NULL', 'id_pembangunan' => 'INT NOT NULL'],
        'das_program' => ['id' => 'INT UNSIGNED NOT NULL'],
        'das_peserta_program' => ['program_id' => 'INT NOT NULL'],
        'das_lembaga' => ['penduduk_id_gabungan' => 'INT UNSIGNED NULL'],
        'das_lembaga_anggota' => ['penduduk_id_gabungan' => 'INT UNSIGNED NULL'],
    ];

    public function up(): void
    {
        $this->terapkan(self::KOLOM);
    }

    public function down(): void
    {
        $this->terapkan(self::KOLOM_LAMA);
    }

    private function terapkan(array $daftar): void
    {
        foreach ($daftar as $tabel => $kolom) {
            foreach ($kolom as $nama => $definisi) {
                if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, $nama)) {
                    DB::statement("ALTER TABLE `{$tabel}` MODIFY `{$nama}` {$definisi}");
                }
            }
        }
    }
};
