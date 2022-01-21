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

class DasKategoriKomplainTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('das_kategori_komplain')->delete();

        DB::table('das_kategori_komplain')->insert([
            0 => [
                'id' => '1',
                'slug' => 'infrastruktur-sanitasi-air',
                'nama' => 'Infrastruktur (Sanitasi & Air)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => '3',
                'slug' => 'pendidikan',
                'nama' => 'Pendidikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            2 => [
                'id' => '4',
                'slug' => 'kesehatan',
                'nama' => 'Kesehatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            3 => [
                'id' => '5',
                'slug' => 'anggaran-desa',
                'nama' => 'Anggaran Desa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            4 => [
                'id' => '6',
                'slug' => 'lainnya',
                'nama' => 'Lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
