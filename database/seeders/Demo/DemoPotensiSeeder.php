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

use App\Models\Potensi;
use App\Models\TipePotensi;
use Illuminate\Database\Seeder;

class DemoPotensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori = [
            ['nama_kategori' => 'Kategori 1'],
            ['nama_kategori' => 'Kategori 2'],
        ];

        foreach ($kategori as $k) {
            TipePotensi::create($k);
        }

        $kategori_id = TipePotensi::first()->id;

        $potensi = [
            [
                'kategori_id' => $kategori_id,
                'nama_potensi' => 'Potensi 1',
                'deskripsi' => 'Deskripsi potensi 1',
                'lokasi' => 'Lokasi potensi 1',
                'file_gambar' => '/img/no-image.png',
            ],
            [
                'kategori_id' => $kategori_id,
                'nama_potensi' => 'Potensi 2',
                'deskripsi' => 'Deskripsi potensi 2',
                'lokasi' => 'Lokasi potensi 2',
                'file_gambar' => '/img/no-image.png',
            ],
        ];

        foreach ($potensi as $p) {
            Potensi::create($p);
        }
    }
}
