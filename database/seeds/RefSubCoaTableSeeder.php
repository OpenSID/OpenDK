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
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use Illuminate\Database\Seeder;

class RefSubCoaTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref_sub_coa')->truncate();

        DB::table('ref_sub_coa')->insert([
            0 => [
                'id' => 1,
                'type_id' => 4,
                'sub_name' => 'Pendapatan Asli Desa
',
            ],
            1 => [
                'id' => 2,
                'type_id' => 4,
                'sub_name' => 'Transfer',
            ],
            2 => [
                'id' => 3,
                'type_id' => 4,
                'sub_name' => 'Pendapatan Lain-lain',
            ],
            3 => [
                'id' => 1,
                'type_id' => 5,
                'sub_name' => 'Belanja Pegawai',
            ],
            4 => [
                'id' => 2,
                'type_id' => 5,
                'sub_name' => 'Belanja Barang dan Jasa',
            ],
            5 => [
                'id' => 3,
                'type_id' => 5,
                'sub_name' => 'Belanja Modal',
            ],
            6 => [
                'id' => 4,
                'type_id' => 5,
                'sub_name' => 'Belanja Tak Terduga',
            ],
            7 => [
                'id' => 1,
                'type_id' => 6,
                'sub_name' => 'Penerimaan Pembiayaan',
            ],
            8 => [
                'id' => 2,
                'type_id' => 6,
                'sub_name' => 'Pengeluaran Pembiayaan',
            ],
        ]);
    }
}
