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
     * Daftar aksi yang tersedia per modul (level 3 permission).
     * Format: 'nama_permission_modul' => ['aksi1', 'aksi2', ...]
     */
    protected array $moduleActions = [
        // Informasi
        'access.informasi.prosedur'         => ['view', 'create', 'edit', 'delete', 'export'],
        'access.informasi.regulasi'         => ['view', 'create', 'edit', 'delete', 'export'],
        'access.informasi.potensi'          => ['view', 'create', 'edit', 'delete'],
        'access.informasi.event'            => ['view', 'create', 'edit', 'delete'],
        'access.informasi.artikel'          => ['view', 'create', 'edit', 'delete'],
        'access.informasi.artikel_kategori' => ['view', 'create', 'edit', 'delete'],
        'access.informasi.komentar_artikel' => ['view', 'edit', 'delete'],
        'access.informasi.faq'              => ['view', 'create', 'edit', 'delete'],
        'access.informasi.form_dokumen'     => ['view', 'create', 'edit', 'delete'],
        'access.informasi.media_sosial'     => ['view', 'create', 'edit', 'delete'],
        'access.informasi.media_terkait'    => ['view', 'create', 'edit', 'delete'],
        'access.informasi.sinergi_program'  => ['view', 'create', 'edit', 'delete'],
        // Publikasi
        'access.publikasi.album'            => ['view', 'create', 'edit', 'delete'],
        'access.publikasi.galeri'           => ['view', 'create', 'edit', 'delete'],
        // Data
        'access.data.profil'                => ['view', 'edit'],
        'access.data.data_umum'             => ['view', 'edit'],
        'access.data.data_desa'             => ['view', 'create', 'edit', 'delete', 'export'],
        'access.data.data_sarana'           => ['view', 'create', 'edit', 'delete', 'export', 'import'],
        'access.data.jabatan'               => ['view', 'create', 'edit', 'delete'],
        'access.data.pengurus'              => ['view', 'create', 'edit', 'delete'],
        'access.data.penduduk'              => ['view', 'export', 'import'],
        'access.data.keluarga'              => ['view', 'export'],
        'access.data.data_suplemen'         => ['view', 'create', 'edit', 'delete', 'export'],
        'access.data.laporan_penduduk'      => ['view', 'delete', 'export', 'import'],
        'access.data.aki_akb'               => ['view', 'edit', 'delete', 'export', 'import'],
        'access.data.imunisasi'             => ['view', 'edit', 'delete', 'export', 'import'],
        'access.data.epidemi_penyakit'      => ['view', 'edit', 'delete', 'export', 'import'],
        'access.data.toilet_sanitasi'       => ['view', 'edit', 'delete', 'export', 'import'],
        'access.data.tingkat_pendidikan'    => ['view', 'delete', 'export', 'import'],
        'access.data.putus_sekolah'         => ['view', 'edit', 'delete', 'export', 'import'],
        'access.data.fasilitas_paud'        => ['view', 'edit', 'delete', 'export', 'import'],
        'access.data.program_bantuan'       => ['view', 'export', 'import'],
        'access.data.anggaran_realisasi'    => ['view', 'edit', 'delete', 'export', 'import'],
        'access.data.anggaran_desa'         => ['view', 'delete', 'export', 'import'],
        'access.data.laporan_apbdes'        => ['view', 'delete', 'export', 'import'],
        'access.data.pembangunan'           => ['view', 'export'],
        'access.data.kategori_lembaga'      => ['view', 'create', 'edit', 'delete'],
        'access.data.lembaga'               => ['view', 'create', 'edit', 'delete'],
        // Admin Komplain
        'access.admin_komplain'             => ['view', 'edit', 'delete'],
        // Setting
        'access.setting.user'               => ['view', 'create', 'edit', 'delete'],
        'access.setting.role'               => ['view', 'create', 'edit', 'delete'],
        'access.setting.slide'              => ['view', 'create', 'edit', 'delete'],
        'access.setting.widget'             => ['view', 'edit'],
        'access.setting.nav_menu'           => ['view', 'create', 'edit', 'delete'],
        'access.setting.navigation'         => ['view', 'create', 'edit', 'delete'],
        'access.setting.komplain_kategori'  => ['view', 'create', 'edit', 'delete'],
        'access.setting.tipe_regulasi'      => ['view', 'create', 'edit', 'delete'],
        'access.setting.jenis_penyakit'     => ['view', 'create', 'edit', 'delete'],
        'access.setting.tipe_potensi'       => ['view', 'create', 'edit', 'delete'],
        'access.setting.coa'                => ['view', 'create', 'edit', 'delete'],
        'access.setting.themes'             => ['view', 'edit'],
        'access.setting.aplikasi'           => ['view', 'edit'],
        'access.setting.info_sistem'        => ['view'],
        'access.setting.database'           => ['view', 'export', 'import'],
        'access.setting.jenis_dokumen'      => ['view', 'create', 'edit', 'delete'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =====================================================================
        // FASE 1: Permission Level 1 & 2 (per modul)
        // =====================================================================
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
                'name'       => $name,
                'slug'       => $name,
                'parent_id'  => 0,
                'sort'       => $sort++,
                'guard_name' => 'web',
            ]);
        }

        // =====================================================================
        // FASE 2: Permission Level 3 (aksi per modul)
        // =====================================================================
        foreach ($this->moduleActions as $moduleName => $actions) {
            // Ambil permission parent (level 2 atau level 1 untuk admin_komplain)
            $parentPermission = Permission::where('name', $moduleName)
                ->where('guard_name', 'web')
                ->first();

            if (! $parentPermission) {
                continue;
            }

            foreach ($actions as $action) {
                $actionName = "{$moduleName}.{$action}";
                Permission::updateOrCreate(
                    ['name' => $actionName, 'guard_name' => 'web'],
                    [
                        'name'       => $actionName,
                        'slug'       => $actionName,
                        'parent_id'  => $parentPermission->id,
                        'sort'       => $sort++,
                        'guard_name' => 'web',
                    ]
                );
            }
        }

        // Refresh cache setelah buat semua permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =====================================================================
        // FASE 3: Roles
        // =====================================================================
        $superAdmin        = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $adminKecamatan    = Role::firstOrCreate(['name' => 'admin-kecamatan', 'guard_name' => 'web']);
        $kontributorArtikel = Role::firstOrCreate(['name' => 'kontributor-artikel', 'guard_name' => 'web']);

        // Super-admin mendapat semua permission (level 1, 2, dan 3)
        $allPermissions = Permission::all()->pluck('name')->toArray();
        $superAdmin->syncPermissions($allPermissions);

        // Admin Kecamatan: semua kecuali setting
        $adminKecamatanPermissions = array_values(array_filter($allPermissions, function ($permission) {
            return ! str_starts_with($permission, 'access.setting');
        }));
        $adminKecamatan->syncPermissions($adminKecamatanPermissions);

        // Kontributor Artikel: hanya informasi artikel + aksi view/create/edit/delete
        $kontributorArtikelPermissions = [
            'access.informasi',
            'access.informasi.artikel',
            'access.informasi.artikel.view',
            'access.informasi.artikel.create',
            'access.informasi.artikel.edit',
            'access.informasi.artikel.delete',
            'access.informasi.artikel_kategori',
            'access.informasi.artikel_kategori.view',
            'access.informasi.komentar_artikel',
            'access.informasi.komentar_artikel.view',
            'access.informasi.komentar_artikel.edit',
            'access.informasi.komentar_artikel.delete',
        ];
        $kontributorArtikel->syncPermissions(
            array_filter($kontributorArtikelPermissions, fn($p) => Permission::where('name', $p)->exists())
        );

        // =====================================================================
        // FASE 4: User admin default
        // =====================================================================
        $user = User::where('email', 'admin@mail.com')->first();

        if ($user === null) {
            $admin = User::create([
                'email'    => 'admin@mail.com',
                'name'     => 'Administrator',
                'gender'   => 'Male',
                'address'  => 'Jakarta',
                'status'   => 1,
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole($superAdmin);
        } else {
            $user->syncRoles([$superAdmin->name]);
        }
    }
}
