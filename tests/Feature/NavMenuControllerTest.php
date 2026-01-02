<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\Artikel;
use App\Models\JenisDokumen;
use App\Models\Kategori;
use App\Models\NavMenu;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withViewErrors([]);
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
        GlobalShareMiddleware::class,
    ]);
    // disabled database gabungan for testing
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
});

test('index displays nav menu page', function () {
    // Seed dummy data
    NavMenu::factory()->create(['name' => 'Menu Utama']);
    Artikel::factory()->create(['judul' => 'Judul Artikel']);
    Kategori::factory()->create(['nama' => 'Kategori']);
    JenisDokumen::factory()->create(['nama' => 'Dokumen']);

    $response = $this->get(route('setting.navmenu.index'));

    $response->assertStatus(200);
    $response->assertViewIs('setting.nav_menu.index');
    $response->assertSee('Pengaturan Menu');
});

test('store saves nav menu', function () {
    $jsonMenu = [
        [
            'text' => 'Menu 1',
            'href' => '/menu-1',
            'target' => '_self',
            'type' => 'link',
            'is_show' => 1,
            'children' => [
                [
                    'text' => 'Submenu 1',
                    'href' => '/menu-1/sub-1',
                    'target' => '_self',
                    'type' => 'link',
                    'is_show' => 1,
                ]
            ]
        ]
    ];

    $response = $this->post(route('setting.navmenu.store'), [
        'json_menu' => json_encode($jsonMenu)
    ]);

    $response->assertRedirect(route('setting.navmenu.index'));
    $this->assertDatabaseHas('nav_menus', [
        'name' => 'Menu 1',
        'url' => '/menu-1',
    ]);
    $this->assertDatabaseHas('nav_menus', [
        'name' => 'Submenu 1',
        'url' => '/menu-1/sub-1',
    ]);
});
