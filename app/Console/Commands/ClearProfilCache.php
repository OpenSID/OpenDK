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
use Illuminate\Support\Facades\Cache;

class ClearProfilCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-profil {--all : Clear all cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache untuk data profil kecamatan dan setting aplikasi';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Membersihkan cache profil kecamatan...');

        // Clear cache individual
        Cache::forget('profil');
        Cache::forget('setting');

        $this->info('✓ Cache profil dan setting berhasil dibersihkan');

        // Clear cache dengan tags jika didukung
        try {
            Cache::tags(['profil', 'kecamatan', 'frontend'])->flush();
            $this->info('✓ Cache dengan tags berhasil dibersihkan');
        } catch (\Exception $e) {
            $this->warn('⚠ Cache tags tidak didukung oleh driver cache saat ini');
        }

        // Jika option --all digunakan, clear semua cache
        if ($this->option('all')) {
            $this->info('Membersihkan semua cache aplikasi...');

            Cache::flush();
            $this->info('✓ Semua cache aplikasi berhasil dibersihkan');

            // Clear cache config dan route juga
            $this->call('config:clear');
            $this->call('route:clear');
            $this->call('view:clear');

            $this->info('✓ Cache config, route, dan view berhasil dibersihkan');
        }

        $this->info('');
        $this->info('Cache profil kecamatan berhasil dibersihkan!');
        $this->info('Data profil kecamatan akan dimuat ulang dari database pada akses berikutnya.');

        return 0;
    }
}
