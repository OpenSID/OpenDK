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

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixStoragePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Fix #1713: direktori di storage/app/public yang terlanjur dibuat dengan
     * permission 0700 oleh Flysystem v3 menyebabkan HTTP 404 pada setup
     * multi-tenant (OLS/Nginx + PHP-FPM) karena web server tidak dapat
     * melakukan directory traversal.
     *
     * @var string
     */
    protected $signature = 'storage:fix-permissions
                            {--dry-run : Preview direktori yang akan diperbaiki tanpa mengubah apapun}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbaiki permission direktori di storage/app/public dari 0700 menjadi 0755 (Fix #1713)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Tidak relevan di Windows (tidak ada permission unix)
        if (PHP_OS_FAMILY === 'Windows') {
            $this->warn('Perintah ini tidak diperlukan di Windows (tidak ada permission Unix).');
            return self::SUCCESS;
        }

        $isDryRun  = $this->option('dry-run');
        $storagePath = Storage::disk('public')->path('');

        if (! is_dir($storagePath)) {
            $this->error("Direktori tidak ditemukan: {$storagePath}");
            return self::FAILURE;
        }

        $this->info('Memeriksa direktori di: ' . $storagePath);
        if ($isDryRun) {
            $this->warn('[DRY-RUN] Tidak ada perubahan yang akan diterapkan.');
        }

        $fixed   = 0;
        $skipped = 0;
        $errors  = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if (! $item->isDir()) {
                continue;
            }

            $dirPath = $item->getRealPath();
            $perms   = fileperms($dirPath) & 0777;

            // Perbaiki jika permission kurang dari 0755
            if ($perms < 0755) {
                $octal = sprintf('%04o', $perms);
                $this->line("  [{$octal} → 0755] {$dirPath}");

                if (! $isDryRun) {
                    if (chmod($dirPath, 0755)) {
                        $fixed++;
                    } else {
                        $this->error("  Gagal chmod: {$dirPath}");
                        $errors++;
                    }
                } else {
                    $fixed++;
                }
            } else {
                $skipped++;
            }
        }

        $this->newLine();

        if ($isDryRun) {
            $this->info("[DRY-RUN] {$fixed} direktori perlu diperbaiki, {$skipped} sudah OK.");
        } else {
            $this->info("Selesai: {$fixed} direktori diperbaiki, {$skipped} sudah OK, {$errors} error.");
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
