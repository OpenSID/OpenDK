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

namespace Database\Seeds\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDasProfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('das_profil')->truncate();

        DB::table('das_profil')->insert([
            'id'                              => 1,
            'provinsi_id'                     => '53',
            'nama_provinsi'                   => 'Nusa Tenggara Timur',
            'kabupaten_id'                    => '53.06',
            'nama_kabupaten'                  => 'FLORES TIMUR',
            'kecamatan_id'                    => '53.06.13',
            'nama_kecamatan'                  => 'Ile Boleng',
            'alamat'                          => 'Jl. Koperasi No. 1, Kab Lombok Barat, Provinsi Nusa Tenggara Barat',
            'kode_pos'                        => '83653',
            'telepon'                         => '0212345234',
            'email'                           => 'admin@mail.com',
            'tahun_pembentukan'               => now()->year,
            'dasar_pembentukan'               => 'PEREGUB No 4 1990',
            'nama_camat'                      => 'H. Hadi Fathurrahman, S.Sos, M.AP',
            'sekretaris_camat'                => 'Drs. Zaenal Abidin',
            'kepsek_pemerintahan_umum'        => 'Musyayad, S.Sos',
            'kepsek_kesejahteraan_masyarakat' => 'Suhartono, S.Sos',
            'kepsek_pemberdayaan_masyarakat'  => 'Asrarudin, SE',
            'kepsek_pelayanan_umum'           => 'Masturi, ST',
            'kepsek_trantib'                  => 'Mastur Idris, SH',
            'file_struktur_organisasi'        => null,
            'file_logo'                       => null,
            'visi'                            => null,
            'misi'                            => null,
            'created_at'                      => now(),
            'updated_at'                      => now(),
        ]);
    }
}
