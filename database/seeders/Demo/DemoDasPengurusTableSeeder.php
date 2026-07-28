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
use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoDasPengurusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('das_pengurus')->truncate();
        Schema::enableForeignKeyConstraints();

        // Pastikan jabatan Kepala Seksi ada (selain Camat dan Sekretaris yang sudah dibuat migration)
        $jabatanKepSek = Jabatan::firstOrCreate(
            ['nama' => 'Kepala Seksi Pemerintahan Umum'],
            ['jenis' => JenisJabatan::JabatanLainnya, 'tupoksi' => 'Membantu Camat dalam menyiapkan bahan perumusan kebijakan, pelaksanaan, evaluasi dan pelaporan urusan pemerintahan umum.']
        );

        $pengurus = [
            [
                'nama'          => 'H. Hadi Fathurrahman',
                'gelar_depan'   => 'H.',
                'gelar_belakang'=> 'S.Sos, M.AP',
                'nip'           => '198201052005011003',
                'nik'           => '5306011205820002',
                'status'        => 1,
                'foto'          => null,
                'tempat_lahir'  => 'Flores Timur',
                'tanggal_lahir' => '1982-01-12',
                'sex'           => 1,
                'pendidikan_id' => 8, // S2
                'agama_id'      => 1, // Islam
                'no_sk'         => 'SK/024/BKD/2020',
                'tanggal_sk'    => '2020-03-01',
                'masa_jabatan'  => 5,
                'pangkat'       => 'Pembina / IV-a',
                'no_henti'      => null,
                'tanggal_henti' => null,
                'jabatan_id'    => JenisJabatan::Camat,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama'          => 'Zaenal Abidin',
                'gelar_depan'   => 'Drs.',
                'gelar_belakang'=> 'M.Si',
                'nip'           => '197005201995031002',
                'nik'           => '5306012005700001',
                'status'        => 1,
                'foto'          => null,
                'tempat_lahir'  => 'Flores Timur',
                'tanggal_lahir' => '1970-05-20',
                'sex'           => 1,
                'pendidikan_id' => 8, // S2
                'agama_id'      => 1, // Islam
                'no_sk'         => 'SK/031/BKD/2019',
                'tanggal_sk'    => '2019-06-01',
                'masa_jabatan'  => 5,
                'pangkat'       => 'Pembina / IV-a',
                'no_henti'      => null,
                'tanggal_henti' => null,
                'jabatan_id'    => JenisJabatan::Sekretaris,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama'          => 'Musyayad',
                'gelar_depan'   => null,
                'gelar_belakang'=> 'S.Sos',
                'nip'           => '197809152003121001',
                'nik'           => '5306011509780001',
                'status'        => 1,
                'foto'          => null,
                'tempat_lahir'  => 'Flores Timur',
                'tanggal_lahir' => '1978-09-15',
                'sex'           => 1,
                'pendidikan_id' => 7, // S1
                'agama_id'      => 1, // Islam
                'no_sk'         => 'SK/047/BKD/2021',
                'tanggal_sk'    => '2021-01-04',
                'masa_jabatan'  => 4,
                'pangkat'       => 'Penata Tk. I / III-d',
                'no_henti'      => null,
                'tanggal_henti' => null,
                'jabatan_id'    => $jabatanKepSek->id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];

        DB::table('das_pengurus')->insert($pengurus);
    }
}
