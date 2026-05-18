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

/**
 * Test untuk memastikan endpoint DataTables pada menu Setting → Pengaturan
 * dapat diakses menggunakan metode POST (antisipasi WAF blocking URL panjang).
 *
 * Sub-menu yang diuji:
 * 1. Tipe Regulasi      — route: setting.tipe-regulasi.getdata      (GET|POST)
 * 2. Grup Pengguna      — route: setting.role.getdata               (GET|POST)
 * 3. Pengguna           — route: setting.user.getdata               (GET|POST)
 * 4. Pengaturan Database — route: setting.pengaturan-database.getdata (GET|POST)
 */

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\Role;
use App\Models\TipeRegulasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

const SETTING_AJAX_HEADERS = ['X-Requested-With' => 'XMLHttpRequest'];

function settingDatatablePostPayload(array $extra = []): array
{
    return array_merge([
        'draw'                      => 1,
        'start'                     => 0,
        'length'                    => 10,
        'search'                    => ['value' => '', 'regex' => 'false'],
        'columns[0][data]'          => 'aksi',
        'columns[0][name]'          => 'aksi',
        'columns[0][searchable]'    => 'false',
        'columns[0][orderable]'     => 'false',
        'columns[0][search][value]' => '',
        'order[0][column]'          => '1',
        'order[0][dir]'             => 'asc',
    ], $extra);
}

beforeEach(function () {
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
        GlobalShareMiddleware::class,
    ]);
});

// =============================================================================
// 1. TIPE REGULASI
// =============================================================================
describe('DataTables Tipe Regulasi via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        TipeRegulasi::create(['nama' => 'Peraturan Desa Test']);

        $response = $this->postJson(
            route('setting.tipe-regulasi.getdata'),
            settingDatatablePostPayload(),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('POST ke getdata mengembalikan kolom yang diharapkan', function () {
        TipeRegulasi::create(['nama' => 'Peraturan Desa Kolom Test']);

        $response = $this->postJson(
            route('setting.tipe-regulasi.getdata'),
            settingDatatablePostPayload(),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('nama', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('setting.tipe-regulasi.getdata'),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});

// =============================================================================
// 2. GRUP PENGGUNA (ROLE)
// =============================================================================
describe('DataTables Grup Pengguna via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        Role::create(['name' => 'Test Role', 'guard_name' => 'web']);

        $response = $this->postJson(
            route('setting.role.getdata'),
            settingDatatablePostPayload(),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('POST ke getdata mengembalikan kolom name dan users_count', function () {
        $role = Role::create(['name' => 'Role Kolom Test', 'guard_name' => 'web']);

        $response = $this->postJson(
            route('setting.role.getdata'),
            settingDatatablePostPayload(),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('name', $firstRow);
        $this->assertArrayHasKey('users_count', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('setting.role.getdata'),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});

// =============================================================================
// 3. PENGGUNA (USER)
// =============================================================================
describe('DataTables Pengguna via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        User::factory()->create(['name' => 'Test User POST']);

        $response = $this->postJson(
            route('setting.user.getdata'),
            settingDatatablePostPayload(),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('POST ke getdata mengembalikan kolom yang diharapkan', function () {
        User::factory()->create([
            'name'  => 'User Kolom Test',
            'email' => 'kolom@test.com',
        ]);

        $response = $this->postJson(
            route('setting.user.getdata'),
            settingDatatablePostPayload(),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('name', $firstRow);
        $this->assertArrayHasKey('email', $firstRow);
        $this->assertArrayHasKey('status', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('setting.user.getdata'),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});

// =============================================================================
// 4. PENGATURAN DATABASE
// =============================================================================
describe('DataTables Pengaturan Database via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $response = $this->postJson(
            route('setting.pengaturan-database.getdata'),
            settingDatatablePostPayload(),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('setting.pengaturan-database.getdata'),
            SETTING_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});
