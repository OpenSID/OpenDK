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

namespace Database\Seeders\Demo;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class DemoSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'judul' => 'Pantai Garassikang',
                'deskripsi' => 'Lokasi: Bulu Jaya, Kecamatan Bangkala Barat, Kabupaten Jeneponto, Sulawesi Selatan',
                'gambar' => 'https://github.com/OpenSID/OpenDK/assets/14155050/6e15ddc5-cf52-490b-b997-5e8b57f1e446',
            ],
            [
                'judul' => 'Batu Siping',
                'deskripsi' => 'Lokasi: Karampuang, Desa Garassikang, Kecamatan Bangkala Barat, Kabupaten Jeneponto, Sulawesi Selatan',
                'gambar' => 'https://github.com/OpenSID/OpenDK/assets/14155050/b9a3ba56-8916-4820-ac50-8961a40a279e',
            ],
            [
                'judul' => 'Bukit Sinalu Bulu Jaya',
                'deskripsi' => 'Lokasi: Bulu Jaya, Kecamatan Bangkala Barat, Kabupaten Jeneponto, Sulawesi Selatan',
                'gambar' => 'https://github.com/OpenSID/OpenDK/assets/14155050/83fcdfde-07eb-4d58-a57f-689b76bcbaa3',
            ],
            [
                'judul' => 'Pantai Tamarunang',
                'deskripsi' => 'Lokasi: Tamarunang, Pabiringa, Kecamatan Binamu, Kabupaten Jeneponto, Sulawesi Selatan',
                'gambar' => 'https://github.com/OpenSID/OpenDK/assets/14155050/2eac1709-fa16-4f14-9bde-9853df9d2534',
            ],
        ];

        Slide::insert($data);
    }
}
