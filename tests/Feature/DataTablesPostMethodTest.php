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
 * Test untuk memastikan endpoint DataTables pada menu Data → Kecamatan
 * dapat diakses menggunakan metode POST (antisipasi WAF blocking URL panjang).
 *
 * Sub-menu yang diuji:
 * 1. Data Desa      — route: data.data-desa.getdata  (GET|POST)
 * 2. Data Sarana    — route: data.data-sarana.getdata (GET|POST)
 * 3. Pengurus       — route: data.pengurus.index      (GET|POST via ajax)
 * 4. Jabatan        — route: data.jabatan.getdata.post (POST)
 */

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\DataDesa;
use App\Models\Jabatan;
use App\Models\Pengurus;
use App\Models\DataSarana;
use App\Enums\KategoriSarana;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

// Header simulasi XMLHttpRequest (wajib untuk endpoint yang cek request()->ajax())
const AJAX_HEADERS = ['X-Requested-With' => 'XMLHttpRequest'];

// Payload minimal DataTables server-side (mirip yang dikirim browser via POST)
function datatablePostPayload(array $extra = []): array
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

    // Nonaktifkan mode database gabungan agar tidak redirect ke view berbeda
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
});

// =============================================================================
// 1. DATA DESA
// =============================================================================
describe('DataTables Data Desa via POST', function () {

    test('endpoint getdata menolak GET dengan URL kosong (tetap backward-compatible)', function () {
        $response = $this->getJson(
            route('data.data-desa.getdata'),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        DataDesa::factory()->create([
            'desa_id' => '3301010099001',
            'nama'    => 'Desa Test POST',
        ]);

        $response = $this->postJson(
            route('data.data-desa.getdata'),
            datatablePostPayload(),
            AJAX_HEADERS
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
        DataDesa::factory()->create([
            'desa_id' => '3301010099002',
            'nama'    => 'Desa Kolom Test',
        ]);

        $response = $this->postJson(
            route('data.data-desa.getdata'),
            datatablePostPayload(),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('desa_id', $firstRow);
        $this->assertArrayHasKey('nama', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('POST dengan draw parameter dikembalikan kembali dalam response', function () {
        $response = $this->postJson(
            route('data.data-desa.getdata'),
            datatablePostPayload(['draw' => 5]),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonPath('draw', 5);
    });
});

// =============================================================================
// 2. DATA SARANA
// =============================================================================
describe('DataTables Data Sarana via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa   = DataDesa::factory()->create(['desa_id' => '3301010099010']);
        DataSarana::factory()->create([
            'desa_id' => $desa->desa_id,
            'kategori' => KategoriSarana::PUSKESMAS,
        ]);

        $response = $this->postJson(
            route('data.data-sarana.getdata'),
            datatablePostPayload(),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('POST dengan filter desa_id memfilter data dengan benar', function () {
        $desa1 = DataDesa::factory()->create(['desa_id' => '3301010099011']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '3301010099012']);

        DataSarana::factory()->create([
            'desa_id' => $desa1->desa_id,
            'nama'    => 'Sarana Desa 1',
            'kategori' => KategoriSarana::PUSKESMAS,
        ]);
        DataSarana::factory()->create([
            'desa_id' => $desa2->desa_id,
            'nama'    => 'Sarana Desa 2',
            'kategori' => KategoriSarana::POSYANDU,
        ]);

        $response = $this->postJson(
            route('data.data-sarana.getdata'),
            datatablePostPayload(['desa_id' => $desa1->desa_id]),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');

        // Hanya data dari desa1 yang dikembalikan
        foreach ($data as $row) {
            $this->assertEquals($desa1->nama, $row['desa']);
        }
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('data.data-sarana.getdata'),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});

// =============================================================================
// 3. PERANGKAT KECAMATAN — PENGURUS
// =============================================================================
describe('DataTables Pengurus via POST', function () {

    test('endpoint dedicated POST getdata mengembalikan struktur DataTables', function () {
        Jabatan::factory()->jabatanLainnya()->create(['nama' => 'Staff Test']);
        Pengurus::factory()->create(['status' => 1]);

        $response = $this->postJson(
            route('data.pengurus.getdata.post'),
            datatablePostPayload(['status' => '1']),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('POST dengan status aktif hanya mengembalikan pengurus aktif', function () {
        Jabatan::factory()->jabatanLainnya()->create(['nama' => 'Staf Aktif Test']);
        Pengurus::factory()->create(['status' => 1]);
        Pengurus::factory()->create(['status' => 0]);

        $response = $this->postJson(
            route('data.pengurus.getdata.post'),
            datatablePostPayload(['status' => '1']),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');

        // Semua item yang dikembalikan harus aktif (status label 'Aktif')
        foreach ($data as $row) {
            $this->assertStringContainsString('Aktif', $row['status']);
            $this->assertStringNotContainsString('Tidak Aktif', $row['status']);
        }
    });

    test('index via GET masih mengembalikan view HTML (bukan ajax)', function () {
        $response = $this->get(route('data.pengurus.index'));

        $response->assertStatus(200);
        $response->assertViewIs('data.pengurus.index');
    });
});

// =============================================================================
// 4. PERANGKAT KECAMATAN — JABATAN
// =============================================================================
describe('DataTables Jabatan via POST', function () {

    test('route POST jabatan mengembalikan struktur DataTables', function () {
        Jabatan::factory()->jabatanLainnya()->create();

        $response = $this->postJson(
            route('data.jabatan.getdata.post'),
            datatablePostPayload(),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });

    test('POST ke jabatan mengembalikan kolom nama dan tupoksi', function () {
        Jabatan::factory()->jabatanLainnya()->create([
            'nama'    => 'Staf Umum Test',
            'tupoksi' => 'Menangani urusan umum',
        ]);

        $response = $this->postJson(
            route('data.jabatan.getdata.post'),
            datatablePostPayload(),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('nama', $firstRow);
        $this->assertArrayHasKey('tupoksi', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('index via GET masih mengembalikan view HTML (bukan ajax)', function () {
        $response = $this->get(route('data.jabatan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('data.jabatan.index');
    });

    test('GET index dengan ajax header mengembalikan data DataTables', function () {
        Jabatan::factory()->jabatanLainnya()->create();

        $response = $this->getJson(
            route('data.jabatan.index'),
            AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
    });
});
