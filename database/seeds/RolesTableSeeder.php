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

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();

        DB::table('roles')->insert([
            0 => [
                'id' => 1,
                'slug' => 'super-admin',
                'name' => 'Super Administrator',
                'permissions' => '{"admin":true}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'slug' => 'admin-desa',
                'name' => 'Admin Desa',
                'permissions' => '{"data-penduduk":true,"data-programbantuan":true,"data-anggarandesa":true}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            2 => [
                'id' => 3,
                'slug' => 'admin-kecamatan',
                'name' => 'Admin Kecamatan',
                'permissions' => '{"data-kecamatan":true,"data-anggaranrealisasi":true,"data-layanan":true}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            3 => [
                'id' => 5,
                'slug' => 'admin-puskesmas',
                'name' => 'Admin Puskesmas',
                'permissions' => '{"data-kesehatan":true}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            4 => [
                'id' => 6,
                'slug' => 'admin-pendidikan',
                'name' => 'Admin Pendidikan',
                'permissions' => '{"data-pendidikan":true}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            5 => [
                'id' => 7,
                'slug' => 'admin-komplain',
                'name' => 'Admin Keluhan',
                'permissions' => '{"adminsikoma":true}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            6 => [
                'id' => 8,
                'slug' => 'administrator-website',
                'name' => 'Administrator Website',
                'permissions' => '{"data":true,"data-kecamatan":true,"data-penduduk":true,"data-kesehatan":true,"data-pendidikan":true,"data-programbantuan":true,"data-anggaranrealisasi":true,"data-anggarandesa":true,"data-layanan":true,"adminsikoma":true,"setting":true,"setting-kategorikomplain":true,"setting-tiperegulasi":true,"setting-jenispenyakit":true,"setting-coa":true,"setting-gruppengguna":true,"setting-pengguna":true}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
