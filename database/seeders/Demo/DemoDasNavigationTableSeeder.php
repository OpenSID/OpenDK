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

use App\Enums\MenuTipe;
use App\Models\Navigation;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDasNavigationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Submenu
        $subMenu = [];

        DB::table('das_data_desa')->get()->each(function ($data) use (&$subMenu) {
            $slug = $data->sebutan_desa . '-' . Str::slug($data->nama);
            $subMenu[] = [
                'parent_id' => Navigation::where('slug', 'desa')->first()->id,
                'name' => ucwords($data->sebutan_desa . ' ' . $data->nama),
                'slug' => $slug,
                'type' => MenuTipe::DESA,
                'url' => 'desa/' . $slug,
                'order' => $data->id,
                'status' => 1,
            ];
        });

        DB::table('das_tipe_potensi')->get()->each(function ($data) use (&$subMenu) {
            $subMenu[] = [
                'parent_id' => Navigation::where('slug', 'potensi')->first()->id,
                'name' => $data->nama_kategori,
                'slug' => 'potensi-' . $data->slug,
                'type' => MenuTipe::POTENSI,
                'url' => 'potensi/' . $data->slug,
                'order' => $data->id,
                'status' => 1,
            ];
        });

        DB::table('das_navigation')->insert($subMenu);
    }
}
