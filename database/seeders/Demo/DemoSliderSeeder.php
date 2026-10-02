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

use App\Models\Slide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DemoSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Slide::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            [
                'judul'     => 'Selamat Datang di Kecamatan Ile Boleng',
                'deskripsi' => 'Kecamatan Ile Boleng – Kabupaten Flores Timur, Provinsi Nusa Tenggara Timur. Melayani dengan tulus, membangun dengan amanah.',
                'gambar'    => 'https://picsum.photos/seed/slider-kecamatan/1280/600',
            ],
            [
                'judul'     => 'Pelayanan Prima untuk Masyarakat',
                'deskripsi' => 'Kami berkomitmen memberikan pelayanan administrasi yang cepat, mudah, dan transparan demi kesejahteraan seluruh warga kecamatan.',
                'gambar'    => 'https://picsum.photos/seed/slider-pelayanan/1280/600',
            ],
            [
                'judul'     => 'Bersama Membangun Desa yang Maju',
                'deskripsi' => 'Program pembangunan infrastruktur, pengembangan SDM, dan pemberdayaan ekonomi masyarakat terus kami tingkatkan demi kemakmuran bersama.',
                'gambar'    => 'https://picsum.photos/seed/slider-pembangunan/1280/600',
            ],
        ];

        foreach ($data as $slide) {
            Slide::create($slide);
        }
    }
}
