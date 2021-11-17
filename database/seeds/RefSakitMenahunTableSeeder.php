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

class RefSakitMenahunTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref_sakit_menahun')->truncate();

        DB::table('ref_sakit_menahun')->insert([
            0 => [
                'id' => 1,
                'nama' => 'JANTUNG',
            ],
            1 => [
                'id' => 2,
                'nama' => 'LEVER',
            ],
            2 => [
                'id' => 3,
                'nama' => 'PARU-PARU',
            ],
            3 => [
                'id' => 4,
                'nama' => 'KANKER',
            ],
            4 => [
                'id' => 5,
                'nama' => 'STROKE',
            ],
            5 => [
                'id' => 6,
                'nama' => 'DIABETES MELITUS',
            ],
            6 => [
                'id' => 7,
                'nama' => 'GINJAL',
            ],
            7 => [
                'id' => 8,
                'nama' => 'MALARIA',
            ],
            8 => [
                'id' => 9,
                'nama' => 'LEPRA/KUSTA',
            ],
            9 => [
                'id' => 10,
                'nama' => 'HIV/AIDS',
            ],
            10 => [
                'id' => 11,
                'nama' => 'GILA/STRESS',
            ],
            11 => [
                'id' => 12,
                'nama' => 'TBC',
            ],
            12 => [
                'id' => 13,
                'nama' => 'ASTHMA',
            ],
            13 => [
                'id' => 14,
                'nama' => 'TIDAK ADA/TIDAK SAKIT',
            ],
        ]);
    }
}
