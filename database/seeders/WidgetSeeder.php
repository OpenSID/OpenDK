<?php
/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use Illuminate\Database\Seeder;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('widget')->truncate();
        DB::table('widget')->insert([
            0 => [
                'isi' => 'camat.blade.php',
                'enabled' => 1,
                'judul' => 'Camat',
                'jenis_widget' => 1,
                'urut' => 1,
                'form_admin' => null,
                'setting' => null,
            ],
            1 => [
                'isi' => 'event.blade.php',
                'enabled' => 1,
                'judul' => 'Event',
                'jenis_widget' => 1,
                'urut' => 2,
                'form_admin' => null,
                'setting' => null,
            ],
            2 => [
                'isi' => 'komplain.blade.php',
                'enabled' => 1,
                'judul' => 'Komplain',
                'jenis_widget' => 1,
                'urut' => 3,
                'form_admin' => null,
                'setting' => null,
            ],
            3 => [
                'isi' => 'media_sosial.blade.php',
                'enabled' => 1,
                'judul' => 'Media Sosial',
                'jenis_widget' => 1,
                'urut' => 4,
                'form_admin' => null,
                'setting' => null,
            ],
            4 => [
                'isi' => 'pengurus.blade.php',
                'enabled' => 1,
                'judul' => 'Pengurus',
                'jenis_widget' => 1,
                'urut' => 5,
                'form_admin' => null,
                'setting' => null,
            ],
            5 => [
                'isi' => 'sinergi_program.blade.php',
                'enabled' => 1,
                'judul' => 'Sinergi Program',
                'jenis_widget' => 1,
                'urut' => 6,
                'form_admin' => null,
                'setting' => null,
            ],
            6 => [
                'isi' => 'visitor.blade.php',
                'enabled' => 1,
                'judul' => 'Pengunjung',
                'jenis_widget' => 1,
                'urut' => 7,
                'form_admin' => null,
                'setting' => null,
            ],
        ]);
    }
}
