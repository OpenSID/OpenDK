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

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RefKategori extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kategori::insert([
            ['nama' => 'Kebijakan Publik', 'slug' => Str::slug('Kebijakan Publik')],
            ['nama' => 'Hukum dan Regulasi', 'slug' => Str::slug('Hukum dan Regulasi')],
            ['nama' => 'Pemerintahan Daerah', 'slug' => Str::slug('Pemerintahan Daerah')],
            ['nama' => 'Politik dan Demokrasi', 'slug' => Str::slug('Politik dan Demokrasi')],
            ['nama' => 'Pelayanan Publik', 'slug' => Str::slug('Pelayanan Publik')],
            ['nama' => 'Keuangan dan Anggaran', 'slug' => Str::slug('Keuangan dan Anggaran')],
            ['nama' => 'Reformasi Birokrasi', 'slug' => Str::slug('Reformasi Birokrasi')],
            ['nama' => 'Pembangunan dan Infrastruktur', 'slug' => Str::slug('Pembangunan dan Infrastruktur')],
            ['nama' => 'Keamanan dan Pertahanan', 'slug' => Str::slug('Keamanan dan Pertahanan')],
            ['nama' => 'Lingkungan dan Energi', 'slug' => Str::slug('Lingkungan dan Energi')],
            ['nama' => 'Hubungan Internasional', 'slug' => Str::slug('Hubungan Internasional')],
            ['nama' => 'Pendidikan dan Kebudayaan', 'slug' => Str::slug('Pendidikan dan Kebudayaan')],
            ['nama' => 'Kesejahteraan Sosial', 'slug' => Str::slug('Kesejahteraan Sosial')],
            ['nama' => 'Tenaga Kerja dan Ketenagakerjaan', 'slug' => Str::slug('Tenaga Kerja dan Ketenagakerjaan')],
            ['nama' => 'Kesehatan Masyarakat', 'slug' => Str::slug('Kesehatan Masyarakat')],
        ]);
    }
}
