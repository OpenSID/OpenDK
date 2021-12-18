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

class RefPendidikanKkTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref_pendidikan_kk')->truncate();

        DB::table('ref_pendidikan_kk')->insert([
            0 => [
                'id' => 1,
                'nama' => 'TIDAK / BELUM SEKOLAH',
            ],
            1 => [
                'id' => 2,
                'nama' => 'BELUM TAMAT SD/SEDERAJAT',
            ],
            2 => [
                'id' => 3,
                'nama' => 'TAMAT SD / SEDERAJAT',
            ],
            3 => [
                'id' => 4,
                'nama' => 'SLTP/SEDERAJAT',
            ],
            4 => [
                'id' => 5,
                'nama' => 'SLTA / SEDERAJAT',
            ],
            5 => [
                'id' => 6,
                'nama' => 'DIPLOMA I / II',
            ],
            6 => [
                'id' => 7,
                'nama' => 'AKADEMI/ DIPLOMA III/S. MUDA',
            ],
            7 => [
                'id' => 8,
                'nama' => 'DIPLOMA IV/ STRATA I',
            ],
            8 => [
                'id' => 9,
                'nama' => 'STRATA II',
            ],
            9 => [
                'id' => 10,
                'nama' => 'STRATA III',
            ],
        ]);
    }
}
