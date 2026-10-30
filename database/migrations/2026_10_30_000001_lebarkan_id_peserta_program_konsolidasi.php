<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * Lanjutan 2026_10_13_000001_lebarkan_kolom_id_konsolidasi.
 *
 * `SinkronPesertaBantuan` menulis `id` baris peserta dari OpenSID ke `das_peserta_program.id`
 * (kunci utama `INT UNSIGNED` auto-increment, maks. 4.294.967.295). Pada database gabungan Premium id
 * baris desa digeser sebesar `slot x 100.000.000.000`, jadi dorongan peserta bantuan dari desa yang sudah
 * pindah gagal dengan "Out of range" pada kolom ini. Tanda unsigned, NOT NULL, dan auto-increment dipertahankan.
 * Baris lama tidak digeser: baris peserta dikenali lewat (desa_id, program_id, kartu_nik), bukan id.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->ubah('BIGINT UNSIGNED');
    }

    /** Gagal bila sudah ada id di atas 4.294.967.295: data seperti itu memang tidak muat di tipe lama. */
    public function down(): void
    {
        $this->ubah('INT UNSIGNED');
    }

    private function ubah(string $tipe): void
    {
        if (Schema::hasTable('das_peserta_program') && Schema::hasColumn('das_peserta_program', 'id')) {
            DB::statement("ALTER TABLE `das_peserta_program` MODIFY `id` {$tipe} NOT NULL AUTO_INCREMENT");
        }
    }
};
