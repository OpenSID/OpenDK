<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

use Illuminate\Database\Seeder;

class RefCaraKbTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref_cara_kb')->truncate();

        DB::table('ref_cara_kb')->insert([
            0 => [
                'id' => 1,
                'nama' => 'Pil',
                'sex' => 2,
            ],
            1 => [
                'id' => 2,
                'nama' => 'IUD',
                'sex' => 2,
            ],
            2 => [
                'id' => 3,
                'nama' => 'Suntik',
                'sex' => 2,
            ],
            3 => [
                'id' => 4,
                'nama' => 'Kondom',
                'sex' => 1,
            ],
            4 => [
                'id' => 5,
                'nama' => 'Susuk KB',
                'sex' => 2,
            ],
            5 => [
                'id' => 6,
                'nama' => 'Sterilisasi Wanita',
                'sex' => 2,
            ],
            6 => [
                'id' => 7,
                'nama' => 'Sterilisasi Pria',
                'sex' => 1,
            ],
            7 => [
                'id' => 99,
                'nama' => 'Lainnya',
                'sex' => 3,
            ],
        ]);
    }
}
