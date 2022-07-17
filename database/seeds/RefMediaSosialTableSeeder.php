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

class RefMediaSosialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('media_sosial')->truncate();

        DB::table('media_sosial')->insert([
            0 => [
                'id'     => 1,
                'gambar' => 'fb.png',
                'link'   => null,
                'nama'   => 'Facebook',
                'tipe'   => 1,
                'status' => 0,
            ],
            1 => [
                'id'     => 2,
                'gambar' => 'twt.png',
                'link'   => null,
                'nama'   => 'Twitter',
                'tipe'   => 1,
                'status' => 0,
            ],
            2 => [
                'id'     => 3,
                'gambar' => 'yt.png',
                'link'   => null,
                'nama'   => 'YouTube',
                'tipe'   => 1,
                'status' => 0,
            ],
            3 => [
                'id'     => 4,
                'gambar' => 'ins.png',
                'link'   => null,
                'nama'   => 'Instagram',
                'tipe'   => 1,
                'status' => 0,
            ],
            4 => [
                'id'     => 5,
                'gambar' => 'wa.png',
                'link'   => null,
                'nama'   => 'WhatsApp',
                'tipe'   => 1,
                'status' => 0,
            ],
            5 => [
                'id'     => 6,
                'gambar' => 'tg.png',
                'link'   => null,
                'nama'   => 'Telegram',
                'tipe'   => 1,
                'status' => 0,
            ],
        ]);
    }
}
