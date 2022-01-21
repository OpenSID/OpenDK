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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DasProfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $socialmedia = [
            0 => [
                "icon" => "fa fa-facebook",
                "link"=> null
            ],
            1 => [
                "icon"=> "fa fa-twitter",
                "link"=> null
            ],
            2 => [
                "icon"=> "fa fa-instagram",
                "link"=> null
            ],
            3 => [
                "icon"=> "fa fa-youtube",
                "link"=> null
            ],
        ];

        DB::table('das_profil')->truncate();

        DB::table('das_profil')->insert([
            'id'                              => 1,
            'provinsi_id'                     => null,
            'nama_provinsi'                   => null,
            'kabupaten_id'                    => null,
            'nama_kabupaten'                  => null,
            'kecamatan_id'                    => null,
            'nama_kecamatan'                  => null,
            'alamat'                          => null,
            'kode_pos'                        => null,
            'telepon'                         => null,
            'email'                           => null,
            'tahun_pembentukan'               => null,
            'dasar_pembentukan'               => null,
            'nama_camat'                      => null,
            'sekretaris_camat'                => null,
            'kepsek_pemerintahan_umum'        => null,
            'kepsek_kesejahteraan_masyarakat' => null,
            'kepsek_pemberdayaan_masyarakat'  => null,
            'kepsek_pelayanan_umum'           => null,
            'kepsek_trantib'                  => null,
            'file_struktur_organisasi'        => null,
            'file_logo'                       => null,
            'visi'                            => null,
            'misi'                            => null,
            'socialmedia'                     => json_encode($socialmedia),
            'created_at'                      => now(),
            'updated_at'                      => now(),
        ]);
    }
}
