<?php

use App\Exports\ExportSuplemen;
use App\Exports\ExportSuplemenTerdata;
use App\Exports\ExportSuplemenTerdataGabungan;
use App\Models\DataDesa;
use App\Models\Penduduk;
use App\Models\Suplemen;
use App\Models\SuplemenTerdata;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware();
    Suplemen::query()->delete();
});

test('export excel suplemen', function () {
    Suplemen::factory()->count(3)->create();

    Excel::fake();
    $response = $this->get('/data/data-suplemen/export-excel');

    $response->assertSuccessful();
});

test('export excel suplemen with filters', function () {
    Suplemen::factory()->create(['nama' => 'Suplemen Test 1', 'sasaran' => 1]);
    Suplemen::factory()->create(['nama' => 'Suplemen Test 2', 'sasaran' => 2]);

    Excel::fake();
    $response = $this->get('/data/data-suplemen/export-excel?nama=Test&sasaran=1');

    $response->assertSuccessful();
});

test('export suplemen no filter', function () {
    Suplemen::factory()->count(5)->create();

    $export = new ExportSuplemen([]);
    $collection = $export->collection();

    expect($collection->count())->toBe(Suplemen::count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export suplemen with nama filter', function () {
    Suplemen::factory()->create(['nama' => 'BLT Dana Desa']);
    Suplemen::factory()->create(['nama' => 'PKH Keluarga']);
    Suplemen::factory()->create(['nama' => 'Bantuan Pangan']);

    $export = new ExportSuplemen(['nama' => 'BLT']);
    $collection = $export->collection();

    expect($collection->count())->toBe(1)
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export suplemen with sasaran filter', function () {
    Suplemen::create(['nama' => 'Suplemen Penduduk 1', 'slug' => 'suplemen-penduduk-1', 'sasaran' => 1, 'keterangan' => 'Test']);
    Suplemen::create(['nama' => 'Suplemen Penduduk 2', 'slug' => 'suplemen-penduduk-2', 'sasaran' => 1, 'keterangan' => 'Test']);
    Suplemen::create(['nama' => 'Suplemen Keluarga 1', 'slug' => 'suplemen-keluarga-1', 'sasaran' => 2, 'keterangan' => 'Test']);
    Suplemen::create(['nama' => 'Suplemen Keluarga 2', 'slug' => 'suplemen-keluarga-2', 'sasaran' => 2, 'keterangan' => 'Test']);
    Suplemen::create(['nama' => 'Suplemen Keluarga 3', 'slug' => 'suplemen-keluarga-3', 'sasaran' => 2, 'keterangan' => 'Test']);

    $export = new ExportSuplemen(['sasaran' => 1]);
    $collection = $export->collection();

    expect($collection->count())->toBe(2);
});

test('export excel suplemen terdata', function () {
    $suplemen = Suplemen::create([
        'nama' => 'Test Suplemen',
        'slug' => 'test-suplemen',
        'sasaran' => 1,
        'keterangan' => 'Test keterangan'
    ]);

    $desa = DataDesa::factory()->create();
    $pendudukId = 999999;
    $penduduk = Penduduk::forceCreate([
        'id' => $pendudukId,
        'nama' => 'Test Penduduk',
        'nik' => '1234567890123456',
        'desa_id' => $desa->desa_id,
        'status_dasar' => 1,
        'sex' => 1,
        'alamat' => 'Test Alamat',
        'tempat_lahir' => 'Test Tempat Lahir',
        'tanggal_lahir' => '1990-01-01',
    ]);

    SuplemenTerdata::create([
        'suplemen_id' => $suplemen->id,
        'penduduk_id' => $penduduk->id,
        'keterangan' => 'Test keterangan terdata'
    ]);

    Excel::fake();
    $response = $this->get("/data/data-suplemen/export-terdata-excel/{$suplemen->id}");

    $response->assertSuccessful();
});

test('export suplemen terdata with desa filter', function () {
    $suplemen = Suplemen::create([
        'nama' => 'Test Suplemen Filter',
        'slug' => 'test-suplemen-filter',
        'sasaran' => 1,
        'keterangan' => 'Test keterangan filter'
    ]);

    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    $pendudukId1 = 999998;
    $pendudukId2 = 999997;

    $penduduk1 = Penduduk::forceCreate([
        'id' => $pendudukId1,
        'nama' => 'Test Penduduk 1',
        'nik' => '1111567890123456',
        'desa_id' => $desa1->desa_id,
        'status_dasar' => 1,
        'sex' => 1,
        'alamat' => 'Test Alamat 1',
        'tempat_lahir' => 'Test Tempat Lahir 1',
        'tanggal_lahir' => '1990-01-01',
    ]);

    $penduduk2 = Penduduk::forceCreate([
        'id' => $pendudukId2,
        'nama' => 'Test Penduduk 2',
        'nik' => '2222567890123456',
        'desa_id' => $desa2->desa_id,
        'status_dasar' => 1,
        'sex' => 2,
        'alamat' => 'Test Alamat 2',
        'tempat_lahir' => 'Test Tempat Lahir 2',
        'tanggal_lahir' => '1991-01-01',
    ]);

    SuplemenTerdata::create(['suplemen_id' => $suplemen->id, 'penduduk_id' => $penduduk1->id, 'keterangan' => 'Test terdata 1']);
    SuplemenTerdata::create(['suplemen_id' => $suplemen->id, 'penduduk_id' => $penduduk2->id, 'keterangan' => 'Test terdata 2']);

    Excel::fake();
    $response = $this->get("/data/data-suplemen/export-terdata-excel/{$suplemen->id}?desa={$desa1->desa_id}");

    $response->assertSuccessful();
});

test('export suplemen headings', function () {
    $export = new ExportSuplemen([]);
    $headings = $export->headings();

    $expectedHeadings = [
        'ID',
        'Nama Suplemen',
        'Slug',
        'Sasaran',
        'Keterangan',
        'Jumlah Terdata',
        'Tanggal Dibuat',
        'Tanggal Diperbarui',
    ];

    expect($headings)->toBe($expectedHeadings);
});

test('export suplemen mapping', function () {
    $suplemen = Suplemen::factory()->create([
        'nama' => 'Test Suplemen',
        'slug' => 'test-suplemen',
        'sasaran' => 1,
        'keterangan' => 'Test Keterangan'
    ]);

    $export = new ExportSuplemen([]);
    $mappedData = $export->map($suplemen);

    expect($mappedData)->toBeArray()
        ->and($mappedData[0])->toBe($suplemen->id)
        ->and($mappedData[1])->toBe('Test Suplemen')
        ->and($mappedData[2])->toBe('test-suplemen')
        ->and($mappedData[3])->toBe('Penduduk')
        ->and($mappedData[4])->toBe('Test Keterangan');
});

test('export suplemen terdata headings', function () {
    $export = new ExportSuplemenTerdata();
    $headings = $export->headings();

    $expectedHeadings = [
        'ID',
        'Nama Suplemen',
        'NIK',
        'Nama Penduduk',
        'Jenis Kelamin',
        'Tempat Lahir',
        'Tanggal Lahir',
        'Umur',
        'Alamat',
        'Desa',
        'Keterangan Suplemen',
        'Tanggal Dibuat',
        'Tanggal Diperbarui',
    ];

    expect($headings)->toBe($expectedHeadings);
});

test('export suplemen styles', function () {
    $export = new ExportSuplemen([]);
    $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);
    $styles = $export->styles($worksheet);

    expect($styles)->toBeArray();
});

// =============================================================================
// EXPORT SUPLEMEN TERDATA BY ID TESTS
// =============================================================================

test('export suplemen terdata by id', function () {
    // Arrange: Buat data suplemen dan terdata
    Suplemen::query()->delete();
    SuplemenTerdata::query()->delete();

    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);

    $suplemen = Suplemen::create([
        'nama' => 'Test Suplemen Terdata',
        'slug' => 'test-suplemen-terdata',
        'sasaran' => 1,
        'keterangan' => 'Test Keterangan'
    ]);

    SuplemenTerdata::create([
        'suplemen_id' => $suplemen->id,
        'penduduk_id' => $penduduk->id,
        'keterangan' => 'Test Terdata'
    ]);

    // Act: Export terdata by suplemen ID
    $export = new ExportSuplemenTerdata($suplemen->id);
    $collection = $export->collection();

    // Assert: Harus mengembalikan data yang sesuai
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->suplemen_id)->toBe($suplemen->id)
        ->and($collection->first()->penduduk_id)->toBe($penduduk->id);
});

test('export suplemen terdata by id with invalid id returns empty', function () {
    // Arrange: ID yang tidak valid
    $invalidId = 99999;

    // Act: Export dengan ID tidak valid
    $export = new ExportSuplemenTerdata($invalidId);
    $collection = $export->collection();

    // Assert: Harus mengembalikan collection kosong
    expect($collection)->toHaveCount(0);
});

test('export suplemen terdata with gabungan resolver fills penduduk relation', function () {
    Suplemen::query()->delete();
    SuplemenTerdata::query()->delete();

    Http::fake([
        'https://api.example.com/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            'data' => [
                [
                    'id' => 500,
                    'attributes' => [
                        'nama' => 'Penduduk Dari API',
                        'nik' => '1234567890123456',
                        'sex' => 1,
                        'config' => ['kode_desa' => '3301010001', 'nama_desa' => 'Desa API'],
                    ],
                ],
            ],
            'meta' => ['pagination' => ['total' => 1]],
        ], 200),
    ]);

    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-api-key']);

    $suplemen = Suplemen::create([
        'nama' => 'Suplemen Gabungan',
        'slug' => 'suplemen-gabungan',
        'sasaran' => 1,
        'keterangan' => 'Test',
    ]);

    SuplemenTerdata::create([
        'suplemen_id' => $suplemen->id,
        'penduduk_id_gabungan' => 500,
        'desa_id' => '3301010001',
        'keterangan' => 'A1',
    ]);

    $export = new ExportSuplemenTerdataGabungan($suplemen->id, [], [500]);

    $collection = $export->collection();

    expect($collection)->toHaveCount(1)
        ->and($collection->first()->penduduk_id_gabungan)->toBe(500)
        ->and($collection->first()->desa_id)->toBe('3301010001')
        ->and($collection->first()->penduduk)->not->toBeNull()
        ->and($collection->first()->penduduk->nama)->toBe('Penduduk Dari API');
});

test('export suplemen terdata excel calls gabungan api when gabungan active', function () {
    SettingAplikasi::updateOrCreate(['key' => 'sinkronisasi_database_gabungan'], ['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-api-key']);

    Http::fake([
        'https://api.example.com/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            'data' => [
                [
                    'id' => 500,
                    'attributes' => [
                        'nama' => 'Penduduk API Export',
                        'nik' => '1234567890123456',
                        'sex' => 1,
                        'config' => ['kode_desa' => '3301010001', 'nama_desa' => 'Desa API'],
                    ],
                ],
            ],
            'meta' => ['pagination' => ['total' => 1]],
        ], 200),
        '*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => 0]],
        ], 200),
    ]);

    $suplemen = Suplemen::create([
        'nama' => 'Suplemen Gabungan Export',
        'slug' => 'suplemen-gabungan-export',
        'sasaran' => 1,
        'keterangan' => 'Test',
    ]);

    SuplemenTerdata::create([
        'suplemen_id' => $suplemen->id,
        'penduduk_id_gabungan' => 500,
        'desa_id' => '3301010001',
        'keterangan' => 'A1',
    ]);

    $response = $this->get("/data/data-suplemen/export-terdata-excel/{$suplemen->id}");

    $response->assertSuccessful();
    Http::assertSent(fn ($request) => str_contains($request->url(), 'api/v1/opendk/sync-penduduk-opendk'));
});

test('export suplemen terdata by id with multiple terdata', function () {
    // Arrange: Buat data suplemen dengan multiple terdata
    Suplemen::query()->delete();
    SuplemenTerdata::query()->delete();

    $desa = DataDesa::factory()->create();
    $penduduk1 = Penduduk::factory()->create(['desa_id' => $desa->desa_id, 'nama' => 'Penduduk 1']);
    $penduduk2 = Penduduk::factory()->create(['desa_id' => $desa->desa_id, 'nama' => 'Penduduk 2']);
    $penduduk3 = Penduduk::factory()->create(['desa_id' => $desa->desa_id, 'nama' => 'Penduduk 3']);

    $suplemen = Suplemen::create([
        'nama' => 'Test Suplemen Multiple',
        'slug' => 'test-suplemen-multiple',
        'sasaran' => 1,
        'keterangan' => 'Test Keterangan'
    ]);

    SuplemenTerdata::create([
        'suplemen_id' => $suplemen->id,
        'penduduk_id' => $penduduk1->id,
        'keterangan' => 'Terdata 1'
    ]);
    SuplemenTerdata::create([
        'suplemen_id' => $suplemen->id,
        'penduduk_id' => $penduduk2->id,
        'keterangan' => 'Terdata 2'
    ]);
    SuplemenTerdata::create([
        'suplemen_id' => $suplemen->id,
        'penduduk_id' => $penduduk3->id,
        'keterangan' => 'Terdata 3'
    ]);

    // Act: Export terdata by suplemen ID
    $export = new ExportSuplemenTerdata($suplemen->id);
    $collection = $export->collection();

    // Assert: Harus mengembalikan semua terdata
    expect($collection)->toHaveCount(3)
        ->and($collection->every(fn($item) => $item->suplemen_id === $suplemen->id))->toBeTrue();
});
