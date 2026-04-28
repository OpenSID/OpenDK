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

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSpatieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'access.dashboard',
            'access.counter',
            'access.change_default',
            'access.informasi',
            'access.informasi.prosedur',
            'access.informasi.regulasi',
            'access.informasi.potensi',
            'access.informasi.event',
            'access.informasi.artikel',
            'access.informasi.artikel_kategori',
            'access.informasi.komentar_artikel',
            'access.informasi.faq',
            'access.informasi.form_dokumen',
            'access.informasi.media_sosial',
            'access.informasi.media_terkait',
            'access.informasi.sinergi_program',
            'access.publikasi',
            'access.publikasi.album',
            'access.publikasi.galeri',
            'access.kerjasama',
            'access.data',
            'access.data.profil',
            'access.data.data_umum',
            'access.data.data_desa',
            'access.data.data_sarana',
            'access.data.jabatan',
            'access.data.pengurus',
            'access.data.penduduk',
            'access.data.keluarga',
            'access.data.data_suplemen',
            'access.data.laporan_penduduk',
            'access.data.aki_akb',
            'access.data.imunisasi',
            'access.data.epidemi_penyakit',
            'access.data.toilet_sanitasi',
            'access.data.tingkat_pendidikan',
            'access.data.putus_sekolah',
            'access.data.fasilitas_paud',
            'access.data.program_bantuan',
'access.data.anggaran_realisasi',
            'access.data.anggaran_desa',
            'access.data.laporan_apbdes',
            'access.data.pembangunan',
            'access.data.kategori_lembaga',
            'access.data.lembaga',
            'access.admin_komplain',
            'access.pesan',
            'access.surat',
            'access.api',
            'access.setting',
            'access.setting.user',
            'access.setting.role',
            'access.setting.widget',
            'access.setting.nav_menu',
            'access.setting.navigation',
            'access.setting.komplain_kategori',
            'access.setting.tipe_regulasi',
            'access.setting.jenis_penyakit',
            'access.setting.tipe_potensi',
            'access.setting.slide',
            'access.setting.coa',
            'access.setting.themes',
            'access.setting.aplikasi',
            'access.setting.info_sistem',
            'access.setting.database',
            'access.setting.jenis_dokumen',
        ];

        $sort = 1;
        foreach ($permissions as $name) {
            Permission::updateOrCreate(['name' => $name, 'guard_name' => 'web'], [
                'name' => $name,
                'slug' => $name,
                'parent_id' => 0,
                'sort' => $sort++,
                'guard_name' => 'web',
            ]);
        }

$superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $adminKecamatan = Role::firstOrCreate(['name' => 'admin-kecamatan', 'guard_name' => 'web']);
        $kontributorArtikel = Role::firstOrCreate(['name' => 'kontributor-artikel', 'guard_name' => 'web']);

        $superAdmin->syncPermissions($permissions);

        $adminKecamatanPermissions = array_values(array_filter($permissions, function ($permission) {
            return !str_starts_with($permission, 'access.setting');
        }));

        $adminKecamatan->syncPermissions($adminKecamatanPermissions);

        $kontributorArtikelPermissions = [
            'access.informasi',
            'access.informasi.artikel',
            'access.informasi.artikel_kategori',
            'access.informasi.komentar_artikel',
        ];
        $kontributorArtikel->syncPermissions($kontributorArtikelPermissions);

        // cek user admin
        $user = User::where('email', 'admin@mail.com')->first();

        if ($user === null) {
            $admin = User::create([
                'email' => 'admin@mail.com',
                'name' => 'Administrator',
                'gender' => 'Male',
                'address' => 'Jakarta',
                'status' => 1,
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole($superAdmin);
        } else {
            $user->syncRoles([$superAdmin->name]);
        }
    }
}
