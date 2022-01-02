<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

use Illuminate\Database\Seeder;

class RefUmurTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref_umur')->truncate();

        DB::table('ref_umur')->insert([
            0 => [
                'id' => 1,
                'nama' => 'BAYI',
                'dari' => 0,
                'sampai' => 5,
                'status' => 1,
            ],
            1 => [
                'id' => 2,
                'nama' => 'BALITA',
                'dari' => 1,
                'sampai' => 5,
                'status' => 2,
            ],
            2 => [
                'id' => 3,
                'nama' => 'ANAK-ANAK',
                'dari' => 6,
                'sampai' => 14,
                'status' => 1,
            ],
            3 => [
                'id' => 4,
                'nama' => 'REMAJA',
                'dari' => 15,
                'sampai' => 24,
                'status' => 1,
            ],
            4 => [
                'id' => 5,
                'nama' => 'DEWASA',
                'dari' => 25,
                'sampai' => 44,
                'status' => 1,
            ],
            5 => [
                'id' => 6,
                'nama' => 'TUA',
                'dari' => 45,
                'sampai' => 74,
                'status' => 1,
            ],
            6 => [
                'id' => 7,
                'nama' => 'LANSIA',
                'dari' => 75,
                'sampai' => 130,
                'status' => 1,
            ],
        ]);
    }
}
