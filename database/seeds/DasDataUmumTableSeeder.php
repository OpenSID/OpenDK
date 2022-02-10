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
use Illuminate\Support\Facades\DB;

class DasDataUmumTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('das_data_umum')->truncate();

        DB::table('das_data_umum')->insert([
            'id'                     => 1,
            'profil_id'              => 1,
            'tipologi'               => null,
            'ketinggian'             => 1,
            'luas_wilayah'           => 2.0,
            'bts_wil_utara'          => null,
            'bts_wil_timur'          => null,
            'bts_wil_selatan'        => null,
            'bts_wil_barat'          => null,
            'jml_puskesmas'          => 0,
            'jml_puskesmas_pembantu' => 0,
            'jml_posyandu'           => 0,
            'jml_pondok_bersalin'    => 0,
            'jml_paud'               => 0,
            'jml_sd'                 => 0,
            'jml_smp'                => 0,
            'jml_sma'                => 0,
            'jml_masjid_besar'       => 0,
            'jml_mushola'            => 0,
            'jml_gereja'             => 0,
            'jml_pasar'              => 0,
            'jml_balai_pertemuan'    => 0,
            'embed_peta'             => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127672.75772082225!2d100.61093321349074!3d-0.27103862950004254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e2ab5bbf8396485%3A0x56587edf579fe503!2sLuak%2C+Kabupaten+Lima+Puluh+Kota%2C+Sumatera+Barat!5e0!3m2!1sid!2sid!4v1557908807791!5m2!1sid!2sid',
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
    }
}
