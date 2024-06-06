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

use App\Models\Profil;
use App\Models\Regulasi;
use App\Models\TipeRegulasi;
use Illuminate\Database\Seeder;

class DemoRegulasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profil_id = Profil::first()->id;
        $tipe_regulasi = TipeRegulasi::first()->id;

        $data = [
            [
                'profil_id' => $profil_id,
                'tipe_regulasi' => $tipe_regulasi,
                'judul' => 'Regulasi 1',
                'deskripsi' => 'Deskripsi regulasi 1',
                'file_regulasi' => 'storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf',
                'mime_type' => 'pdf',
            ],
            [
                'profil_id' => $profil_id,
                'tipe_regulasi' => $tipe_regulasi,
                'judul' => 'Regulasi 2',
                'deskripsi' => 'Deskripsi regulasi 2',
                'file_regulasi' => 'storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf',
                'mime_type' => 'pdf',
            ],
        ];

        foreach ($data as $regulasi) {
            Regulasi::create($regulasi);
        }
    }
}
