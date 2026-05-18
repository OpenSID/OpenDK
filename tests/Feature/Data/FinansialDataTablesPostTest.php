<?php

/**
 * Test untuk memastikan endpoint DataTables pada menu Data → Finansial
 * dapat diakses menggunakan metode POST (antisipasi WAF blocking URL panjang).
 *
 * Sub-menu yang diuji:
 * 1. Anggaran dan Realisasi  — route: data.anggaran-realisasi.getdata  (GET|POST)
 * 2. APBDes                  — route: data.anggaran-desa.getdata       (GET|POST)
 * 3. Laporan APBDes          — route: data.laporan-apbdes.getdata      (GET|POST)
 */

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\AnggaranRealisasi;
use App\Models\AnggaranDesa;
use App\Models\DataDesa;
use App\Models\LaporanApbdes;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

const FINANSIAL_AJAX_HEADERS = ['X-Requested-With' => 'XMLHttpRequest'];

function finansialDatatablePostPayload(array $extra = []): array
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

    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
});

// =============================================================================
// 1. ANGGARAN DAN REALISASI
// =============================================================================
describe('DataTables Anggaran dan Realisasi via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        AnggaranRealisasi::create([
            'bulan'                => 1,
            'tahun'                => date('Y'),
            'total_anggaran'       => 100000000,
            'total_belanja'        => 80000000,
            'belanja_pegawai'      => 30000000,
            'belanja_barang_jasa'  => 30000000,
            'belanja_modal'        => 20000000,
            'belanja_tidak_langsung' => 0,
        ]);

        $response = $this->postJson(
            route('data.anggaran-realisasi.getdata'),
            finansialDatatablePostPayload(),
            FINANSIAL_AJAX_HEADERS
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
        AnggaranRealisasi::create([
            'bulan'                => 2,
            'tahun'                => date('Y'),
            'total_anggaran'       => 200000000,
            'total_belanja'        => 150000000,
            'belanja_pegawai'      => 50000000,
            'belanja_barang_jasa'  => 50000000,
            'belanja_modal'        => 50000000,
            'belanja_tidak_langsung' => 0,
        ]);

        $response = $this->postJson(
            route('data.anggaran-realisasi.getdata'),
            finansialDatatablePostPayload(),
            FINANSIAL_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('total_anggaran', $firstRow);
        $this->assertArrayHasKey('total_belanja', $firstRow);
        $this->assertArrayHasKey('bulan', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('data.anggaran-realisasi.getdata'),
            FINANSIAL_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});

// =============================================================================
// 2. APBDES
// =============================================================================
describe('DataTables APBDes via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create(['desa_id' => '3301010099100']);
        AnggaranDesa::create([
            'desa_id'  => $desa->desa_id,
            'no_akun'  => '1.1.1',
            'nama_akun' => 'Pendapatan Asli Desa',
            'jumlah'   => 50000000,
            'bulan'    => 1,
            'tahun'    => date('Y'),
        ]);

        $response = $this->postJson(
            route('data.anggaran-desa.getdata'),
            finansialDatatablePostPayload(),
            FINANSIAL_AJAX_HEADERS
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
        $desa = DataDesa::factory()->create(['desa_id' => '3301010099101']);
        AnggaranDesa::create([
            'desa_id'  => $desa->desa_id,
            'no_akun'  => '1.1.2',
            'nama_akun' => 'Dana Desa',
            'jumlah'   => 75000000,
            'bulan'    => 1,
            'tahun'    => date('Y'),
        ]);

        $response = $this->postJson(
            route('data.anggaran-desa.getdata'),
            finansialDatatablePostPayload(),
            FINANSIAL_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('nama_akun', $firstRow);
        $this->assertArrayHasKey('jumlah', $firstRow);
        $this->assertArrayHasKey('bulan', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('POST dengan filter desa_id memfilter data dengan benar', function () {
        $desa1 = DataDesa::factory()->create(['desa_id' => '3301010099102']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '3301010099103']);

        AnggaranDesa::create(['desa_id' => $desa1->desa_id, 'no_akun' => '1.1.1', 'nama_akun' => 'PAD', 'jumlah' => 100000, 'bulan' => 1, 'tahun' => date('Y')]);
        AnggaranDesa::create(['desa_id' => $desa2->desa_id, 'no_akun' => '1.1.2', 'nama_akun' => 'DD', 'jumlah' => 200000, 'bulan' => 1, 'tahun' => date('Y')]);

        $response = $this->postJson(
            route('data.anggaran-desa.getdata'),
            finansialDatatablePostPayload(['desa' => $desa1->desa_id]),
            FINANSIAL_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        foreach ($data as $row) {
            $this->assertEquals($desa1->nama, $row['desa']['nama']);
        }
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('data.anggaran-desa.getdata'),
            FINANSIAL_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});

// =============================================================================
// 3. LAPORAN APBDES
// =============================================================================
describe('DataTables Laporan APBDes via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create(['desa_id' => '3301010099104']);
        LaporanApbdes::create([
            'desa_id'    => $desa->desa_id,
            'judul'      => 'Laporan APBDes Test',
            'tahun'      => date('Y'),
            'semester'   => 1,
            'nama_file'  => 'laporan-test.pdf',
        ]);

        $response = $this->postJson(
            route('data.laporan-apbdes.getdata'),
            finansialDatatablePostPayload(),
            FINANSIAL_AJAX_HEADERS
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
        $desa = DataDesa::factory()->create(['desa_id' => '3301010099105']);
        LaporanApbdes::create([
            'desa_id'    => $desa->desa_id,
            'judul'      => 'Laporan APBDes Kolom Test',
            'tahun'      => date('Y'),
            'semester'   => 1,
            'nama_file'  => 'laporan-kolom-test.pdf',
        ]);

        $response = $this->postJson(
            route('data.laporan-apbdes.getdata'),
            finansialDatatablePostPayload(),
            FINANSIAL_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('judul', $firstRow);
        $this->assertArrayHasKey('nama_desa', $firstRow);
        $this->assertArrayHasKey('tahun', $firstRow);
        $this->assertArrayHasKey('semester', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('POST dengan filter desa_id memfilter data dengan benar', function () {
        $desa1 = DataDesa::factory()->create(['desa_id' => '3301010099106']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '3301010099107']);

        LaporanApbdes::create(['desa_id' => $desa1->desa_id, 'judul' => 'Laporan Desa 1', 'tahun' => date('Y'), 'semester' => 1, 'nama_file' => 'f1.pdf']);
        LaporanApbdes::create(['desa_id' => $desa2->desa_id, 'judul' => 'Laporan Desa 2', 'tahun' => date('Y'), 'semester' => 1, 'nama_file' => 'f2.pdf']);

        $response = $this->postJson(
            route('data.laporan-apbdes.getdata'),
            finansialDatatablePostPayload(['desa' => $desa1->desa_id]),
            FINANSIAL_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        foreach ($data as $row) {
            $this->assertEquals($desa1->nama, $row['nama_desa']);
        }
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('data.laporan-apbdes.getdata'),
            FINANSIAL_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});
