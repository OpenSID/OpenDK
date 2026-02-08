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

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PpidMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cek apakah menu PPID sudah ada
        $existingMenu = DB::table('das_menu')->where('slug', 'ppid')->first();

        if ($existingMenu) {
            $this->command->info('Menu PPID sudah ada. Melewati seeder.');
            return;
        }

        // Insert menu parent PPID
        $ppidMenuId = DB::table('das_menu')->insertGetId([
            'parent_id' => 0,
            'name' => 'PPID',
            'slug' => 'ppid',
            'icon' => 'fa-file-text-o',
            'url' => '#',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert submenu Daftar Dokumen
        DB::table('das_menu')->insert([
            'parent_id' => $ppidMenuId,
            'name' => 'Daftar Dokumen',
            'slug' => 'ppid-dokumen',
            'icon' => '',
            'url' => 'ppid/dokumen',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert submenu Permohonan Informasi
        DB::table('das_menu')->insert([
            'parent_id' => $ppidMenuId,
            'name' => 'Permohonan Informasi',
            'slug' => 'ppid-permohonan',
            'icon' => '',
            'url' => 'ppid/permohonan',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert submenu Jenis Dokumen
        DB::table('das_menu')->insert([
            'parent_id' => $ppidMenuId,
            'name' => 'Jenis Dokumen',
            'slug' => 'ppid-jenis-dokumen',
            'icon' => '',
            'url' => 'ppid/jenis-dokumen',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert submenu Pengaturan
        DB::table('das_menu')->insert([
            'parent_id' => $ppidMenuId,
            'name' => 'Pengaturan',
            'slug' => 'ppid-pengaturan',
            'icon' => '',
            'url' => 'ppid/pengaturan',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Menu PPID berhasil ditambahkan.');
    }
}
