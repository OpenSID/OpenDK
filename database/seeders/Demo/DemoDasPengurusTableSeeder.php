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

use App\Enums\JenisJabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDasPengurusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'nama' => 'H. Hadi Fathurrahman, S.Sos, M.AP',
            'gelar_depan' => null,
            'gelar_belakang' => null,
            'nip' => null,
            'nik' => random_int(16, 16),
            'status' => 1,
            'foto' => null,
            'tempat_lahir' => 'Mangsit',
            'tanggal_lahir' => now(),
            'sex' => 1,
            'pendidikan_id' => 1,
            'agama_id' => 1,
            'no_sk' => null,
            'tanggal_sk' => now(),
            'masa_jabatan' => 5,
            'pangkat' => 'Camat',
            'no_henti' => null,
            'tanggal_henti' => null,
            'jabatan_id' => JenisJabatan::Camat,
            'created_at' => now(),
        ];

        DB::table('das_pengurus')->insert($data);
    }
}
