<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menggeser id tersimpan satu desa setelah desa itu dipindah ke database gabungan konsolidasi
 * (id baru = id lama + offset, offset = slot x 100.000.000.000). Tanpa ini dorongan berikutnya dari desa
 * membuat baris baru di samping baris lama (kunci sinkron: desa_id + id). Dijalankan sekali per desa.
 */
class GeserIdKonsolidasi extends Command
{
    protected $signature = 'konsolidasi:geser-id-desa {desa_id : kode desa, 13 karakter} {offset : slot x 100000000000} {--dry-run : hanya hitung baris}';

    protected $description = 'Geser id tersimpan satu desa setelah konsolidasi database gabungan (sekali per desa)';

    /** tabel => kolom id yang digeser (semua difilter desa_id) */
    private const TARGET = [
        'das_penduduk' => ['id_pend_desa'],
        'das_pembangunan' => ['id'],
        'das_pembangunan_dokumentasi' => ['id', 'id_pembangunan'],
        'das_program' => ['id'],
        'das_peserta_program' => ['program_id'],
        'das_apbdes' => ['id_apbdes'],
        'das_laporan_penduduk' => ['id_laporan_penduduk'],
        'das_suplemen_terdata' => ['penduduk_id_gabungan'],
    ];

    public function handle(): int
    {
        $desa = (string) $this->argument('desa_id');
        $offset = $this->argument('offset');

        if (! preg_match('/^\d{13}$/', $desa)) {
            $this->error('desa_id harus 13 digit');

            return self::INVALID;
        }
        if (! ctype_digit((string) $offset) || (int) $offset <= 0 || (int) $offset % 100000000000 !== 0) {
            $this->error('offset harus kelipatan positif dari 100000000000 (slot x 100000000000)');

            return self::INVALID;
        }
        $offset = (int) $offset;

        if (DB::table('konsolidasi_geseran')->where('desa_id', $desa)->exists()) {
            $this->error("Desa {$desa} sudah digeser; tidak dijalankan dua kali.");

            return self::FAILURE;
        }

        $jumlah = [];
        foreach (self::TARGET as $tabel => $kolom) {
            if (! Schema::hasTable($tabel)) {
                continue;
            }
            $jumlah[$tabel] = DB::table($tabel)->where('desa_id', $desa)->count();
        }

        $lembaga = Schema::hasTable('das_lembaga')
            ? DB::table('das_lembaga')->whereNotNull('penduduk_id_gabungan')->count()
              + DB::table('das_lembaga_anggota')->whereNotNull('penduduk_id_gabungan')->count()
            : 0;

        foreach ($jumlah as $tabel => $n) {
            $this->line(sprintf('%-30s %d baris', $tabel, $n));
        }
        if ($lembaga > 0) {
            $this->warn("{$lembaga} baris lembaga/anggota memuat penduduk_id_gabungan tanpa desa_id: tidak digeser, petakan lewat API.");
        }
        if ($this->option('dry-run')) {
            return self::SUCCESS;
        }

        DB::transaction(function () use ($desa, $offset, $jumlah) {
            foreach (self::TARGET as $tabel => $kolom) {
                if (! isset($jumlah[$tabel])) {
                    continue;
                }
                foreach ($kolom as $nama) {
                    DB::table($tabel)->where('desa_id', $desa)->whereNotNull($nama)->update([$nama => DB::raw("`{$nama}` + {$offset}")]);
                }
            }
            DB::table('konsolidasi_geseran')->insert([
                'desa_id' => $desa,
                'offset' => $offset,
                'jumlah_baris' => json_encode($jumlah),
            ]);
        });

        $this->info("Desa {$desa} digeser sebesar {$offset}.");

        return self::SUCCESS;
    }
}
