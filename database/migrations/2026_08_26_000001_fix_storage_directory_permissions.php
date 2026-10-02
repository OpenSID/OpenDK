<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

/**
 * Fix #1713 — Perbaiki permission direktori di storage/app/public.
 *
 * Flysystem v3 membuat direktori baru dengan permission 0700 (default
 * PortableVisibilityConverter::directoryPrivate) ketika tidak ada
 * konfigurasi `directory_visibility`. Pada setup multi-tenant
 * (OpenLiteSpeed, Nginx + PHP-FPM) di mana file statis dilayani oleh
 * user berbeda dari PHP worker (misal: `nobody` vs `sid_<id>`),
 * direktori 0700 tidak dapat di-traverse → HTTP 404.
 *
 * Migrasi ini menjalankan `storage:fix-permissions` secara otomatis
 * saat `php artisan migrate` dijalankan sebagai bagian dari proses
 * upgrade, sehingga instalasi yang sudah berjalan langsung terproteksi
 * tanpa perlu intervensi manual.
 *
 * up()   → chmod 0755 semua direktori di storage/app/public
 * down() → no-op (tidak membalik permission, sudah aman)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip di Windows — tidak ada permission Unix
        if (PHP_OS_FAMILY === 'Windows') {
            return;
        }

        Artisan::call('storage:fix-permissions');
    }

    /**
     * Reverse the migrations.
     *
     * Tidak ada rollback untuk operasi chmod — mengembalikan direktori ke 0700
     * justru akan merusak aksesibilitas file. down() sengaja dibiarkan no-op.
     */
    public function down(): void
    {
        // intentional no-op
    }
};
