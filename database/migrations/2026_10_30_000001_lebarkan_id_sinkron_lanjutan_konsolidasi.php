<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * Lanjutan 2026_10_13_000001_lebarkan_kolom_id_konsolidasi.
 *
 * Pada database gabungan Premium id baris desa digeser `slot x 100.000.000.000`. Dua kolom `INT` yang diisi dengan
 * id laporan dari OpenSID belum dilebarkan, sehingga dorongan data dari desa yang sudah pindah gagal dengan
 * "Out of range": `das_apbdes.id_apbdes` dan `das_laporan_penduduk.id_laporan_penduduk`. `LaporanApbdesQueueJob`
 * dan `LaporanPendudukQueueJob` menulisnya lewat query builder (tidak dibatasi `$fillable`) dan memakainya sebagai
 * kunci upsert bersama `desa_id`; karena itu kedua kolom juga digeser oleh `konsolidasi:geser-id-desa`.
 *
 * `das_peserta_program.id` sengaja tidak diubah: `PesertaProgram::$fillable` tidak memuat `id`, jadi kolom itu
 * selalu diisi auto-increment OpenDK dan tidak pernah menerima id dari OpenSID.
 *
 * Tanda (signed/unsigned), NOT NULL, dan NULL dipertahankan; tidak ada FK yang mengacu ke kolom ini.
 */
return new class extends Migration
{
    /** tabel => [kolom => definisi baru] */
    private const KOLOM = [
        'das_apbdes' => ['id_apbdes' => 'BIGINT NULL'],
        'das_laporan_penduduk' => ['id_laporan_penduduk' => 'BIGINT NOT NULL'],
    ];

    /** Tipe sebelum pelebaran, untuk down(). */
    private const KOLOM_LAMA = [
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
