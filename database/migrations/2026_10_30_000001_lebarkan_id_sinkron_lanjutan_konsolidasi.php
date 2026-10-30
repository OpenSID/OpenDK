<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * Lanjutan 2026_10_13_000001_lebarkan_kolom_id_konsolidasi.
 *
 * Pada database gabungan Premium id baris desa digeser `slot x 100.000.000.000`. Tiga kolom yang diisi dengan
 * id baris OpenSID belum dilebarkan, sehingga dorongan data dari desa yang sudah pindah gagal dengan "Out of range":
 *
 * - `das_peserta_program.id`: kunci utama `INT UNSIGNED` auto-increment; `SinkronPesertaBantuan` menulis
 *   `id` peserta dari OpenSID. Baris dikenali lewat (desa_id, program_id, kartu_nik), jadi baris lama tidak digeser.
 * - `das_apbdes.id_apbdes` dan `das_laporan_penduduk.id_laporan_penduduk`: `INT`; `LaporanApbdesQueueJob` dan
 *   `LaporanPendudukQueueJob` menulis `id` laporan dari OpenSID dan memakainya sebagai kunci upsert bersama `desa_id`.
 *   Kedua kolom ikut digeser oleh `konsolidasi:geser-id-desa`.
 *
 * Tanda (signed/unsigned), NOT NULL, dan auto-increment dipertahankan; tidak ada FK yang mengacu ke kolom ini.
 */
return new class extends Migration
{
    /** tabel => [kolom => definisi baru] */
    private const KOLOM = [
        'das_peserta_program' => ['id' => 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT'],
        'das_apbdes' => ['id_apbdes' => 'BIGINT NULL'],
        'das_laporan_penduduk' => ['id_laporan_penduduk' => 'BIGINT NOT NULL'],
    ];

    /** Tipe sebelum pelebaran, untuk down(). */
    private const KOLOM_LAMA = [
        'das_peserta_program' => ['id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT'],
        'das_apbdes' => ['id_apbdes' => 'INT NULL'],
        'das_laporan_penduduk' => ['id_laporan_penduduk' => 'INT NOT NULL'],
    ];

    public function up(): void
    {
        $this->terapkan(self::KOLOM);
    }

    /** Gagal bila sudah ada id di atas batas tipe lama: data seperti itu memang tidak muat di tipe lama. */
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
