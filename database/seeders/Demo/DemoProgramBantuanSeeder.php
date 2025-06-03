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

namespace Database\Seeders\Demo;

use App\Imports\SinkronBantuan;
use App\Imports\SinkronPesertaBantuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use ZipArchive;

class DemoProgramBantuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $name = 'program_bantuan_31_05_2022_opendk.zip';

            // Temporary path file
            $path = storage_path("app/public/template_upload/{$name}");
            $extract = storage_path('app/temp/bantuan/');

            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            // Proses impor excell
            (new SinkronBantuan())
                ->queue($extract.Str::replaceLast('zip', 'csv', $name));
            (new SinkronPesertaBantuan())
                ->queue($extract.Str::replaceLast('zip', 'csv', 'peserta_'.$name));
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Import data gagal.');
        }
    }
}
