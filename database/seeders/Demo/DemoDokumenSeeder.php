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

use App\Models\FormDokumen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DemoDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        FormDokumen::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            [
                'nama_dokumen' => 'Panduan Pengguna OpenDK',
                'description'  => 'Panduan lengkap penggunaan aplikasi OpenDK (Dashboard Kecamatan Terbuka) untuk administrator dan operator kecamatan.',
                'file_dokumen' => 'storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf',
                'is_published' => true,
                'published_at' => now()->subDays(30),
            ],
            [
                'nama_dokumen' => 'Laporan Realisasi Anggaran Kecamatan Semester I 2024',
                'description'  => 'Laporan realisasi anggaran belanja dan pendapatan kecamatan untuk periode semester pertama Tahun Anggaran 2024.',
                'file_dokumen' => 'storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
            [
                'nama_dokumen' => 'Peraturan Camat tentang Standar Pelayanan Publik',
                'description'  => 'Peraturan Camat Ile Boleng tentang penetapan standar pelayanan publik di lingkungan Kecamatan Ile Boleng.',
                'file_dokumen' => 'storage/template_upload/Panduan_Pengguna_Kecamatan_Dashboard.pdf',
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
        ];

        foreach ($data as $dokumen) {
            FormDokumen::create($dokumen);
        }
    }
}
