<?php

/**
 * Test untuk memastikan endpoint DataTables pada menu Data → Program Bantuan dan Data → Pembangunan
 * dapat diakses menggunakan metode POST (antisipasi WAF blocking URL panjang).
 *
 * Sub-menu yang diuji:
 * 1. Program Bantuan  — route: data.program-bantuan.getdata  (GET|POST)
 * 2. Pembangunan      — route: data.pembangunan.getdata       (GET|POST)
 */

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\DataDesa;
use App\Models\Pembangunan;
use App\Models\Program;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

const BP_AJAX_HEADERS = ['X-Requested-With' => 'XMLHttpRequest'];

function bpDatatablePostPayload(array $extra = []): array
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
// 1. PROGRAM BANTUAN
// =============================================================================
describe('DataTables Program Bantuan via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create(['desa_id' => '3301010099200']);
        Program::factory()->create([
            'nama'    => 'Program Bantuan Test',
            'desa_id' => $desa->desa_id,
        ]);

        $response = $this->postJson(
            route('data.program-bantuan.getdata'),
            bpDatatablePostPayload(),
            BP_AJAX_HEADERS
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
        $desa = DataDesa::factory()->create(['desa_id' => '3301010099201']);
        Program::factory()->create([
            'nama'    => 'Program Bantuan Kolom Test',
            'sasaran' => 2,
            'desa_id' => $desa->desa_id,
        ]);

        $response = $this->postJson(
            route('data.program-bantuan.getdata'),
            bpDatatablePostPayload(),
            BP_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('nama', $firstRow);
        $this->assertArrayHasKey('sasaran', $firstRow);
        $this->assertArrayHasKey('masa_berlaku', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('POST dengan filter desa_id memfilter data dengan benar', function () {
        $desa1 = DataDesa::factory()->create(['desa_id' => '3301010099202']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '3301010099203']);

        Program::factory()->create(['nama' => 'Program Desa 1', 'desa_id' => $desa1->desa_id]);
        Program::factory()->create(['nama' => 'Program Desa 2', 'desa_id' => $desa2->desa_id]);

        $response = $this->postJson(
            route('data.program-bantuan.getdata'),
            bpDatatablePostPayload(['desa' => $desa1->desa_id]),
            BP_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        foreach ($data as $row) {
            $this->assertEquals($desa1->desa_id, $row['desa']['desa_id']);
        }
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('data.program-bantuan.getdata'),
            BP_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});

// =============================================================================
// 2. PEMBANGUNAN
// =============================================================================
describe('DataTables Pembangunan via POST', function () {

    test('endpoint getdata menerima POST dan mengembalikan struktur DataTables', function () {
        $desa = DataDesa::factory()->create(['desa_id' => '3301010099300']);
        Pembangunan::factory()->create([
            'judul'          => 'Pembangunan Jalan Test',
            'tahun_anggaran' => date('Y'),
            'desa_id'        => $desa->desa_id,
        ]);

        $response = $this->postJson(
            route('data.pembangunan.getdata'),
            bpDatatablePostPayload(),
            BP_AJAX_HEADERS
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
        $desa = DataDesa::factory()->create(['desa_id' => '3301010099301']);
        Pembangunan::factory()->create([
            'judul'          => 'Pembangunan Gedung Test',
            'tahun_anggaran' => date('Y'),
            'lokasi'         => 'Dusun 1',
            'volume'         => '100 m2',
            'desa_id'        => $desa->desa_id,
        ]);

        $response = $this->postJson(
            route('data.pembangunan.getdata'),
            bpDatatablePostPayload(),
            BP_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $firstRow = $data[0];
        $this->assertArrayHasKey('judul', $firstRow);
        $this->assertArrayHasKey('lokasi', $firstRow);
        $this->assertArrayHasKey('volume', $firstRow);
        $this->assertArrayHasKey('aksi', $firstRow);
    });

    test('POST dengan filter desa_id memfilter data dengan benar', function () {
        $desa1 = DataDesa::factory()->create(['desa_id' => '3301010099302']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '3301010099303']);

        Pembangunan::factory()->create(['judul' => 'Pembangunan Desa 1', 'tahun_anggaran' => date('Y'), 'desa_id' => $desa1->desa_id]);
        Pembangunan::factory()->create(['judul' => 'Pembangunan Desa 2', 'tahun_anggaran' => date('Y'), 'desa_id' => $desa2->desa_id]);

        $response = $this->postJson(
            route('data.pembangunan.getdata'),
            bpDatatablePostPayload(['desa' => $desa1->desa_id]),
            BP_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $data = $response->json('data');
        foreach ($data as $row) {
            $this->assertStringContainsString('Desa 1', $row['judul']);
        }
    });

    test('GET pada getdata masih berfungsi (backward-compatible)', function () {
        $response = $this->getJson(
            route('data.pembangunan.getdata'),
            BP_AJAX_HEADERS
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    });
});
