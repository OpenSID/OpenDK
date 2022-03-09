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
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create([
            'name' => 'super admin',
            'guard_name' => 'super-admin'
        ]);

        $desa = Role::create([
            'name' => 'admin desa',
            'guard_name' => 'admin-desa'
        ]);

        $kecamatan = Role::create([
            'name' => 'admin kecamatan',
            'guard_name' => 'admin-kecamatan'
        ]);

        $puskesmas = Role::create([
            'name' => 'admin puskesmas',
            'guard_name' => 'admin-puskesmas'
        ]);

        $pendidikan = Role::create([
            'name' => 'admin pendidikan',
            'guard_name' => 'admin-pendidikan'
        ]);

        $komplain = Role::create([
            'name' => 'admin komplain',
            'guard_name' => 'admin-komplain'
        ]);

        $website = Role::create([
            'name' => 'administrator website',
            'guard_name' => 'administrator-website'
        ]);

        $read =  Role::create([
            'name' => 'read',
            'guard_name' => 'read'
        ]);

        $writer =  Role::create([
            'name' => 'write',
            'guard_name' => 'write'
        ]);

        $edit = Role::create([
            'name' => 'edit',
            'guard_name' => 'edit'
        ]);

        $hapus = Role::create([
            'name' => 'hapus',
            'guard_name' => 'hapus'
        ]);

        $admin->givePermissionTo([$read, $writer, $edit, $hapus]);
        $writer->givePermissionTo([$read, $writer, $edit, $hapus]);
        $desa->givePermissionTo([$read, $writer, $edit, $hapus]);
        $kecamatan->givePermissionTo([$read, $writer, $edit, $hapus]);
        $puskesmas->givePermissionTo([$read, $writer, $edit, $hapus]);
        $pendidikan->givePermissionTo([$read, $writer, $edit, $hapus]);
        $komplain->givePermissionTo([$read, $writer, $edit, $hapus]);
        $website->givePermissionTo([$read, $writer, $edit, $hapus]);
    }
}
