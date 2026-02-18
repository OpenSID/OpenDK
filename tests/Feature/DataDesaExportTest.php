<?php

use App\Exports\ExportDataDesa;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Maatwebsite\Excel\Facades\Excel;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware();

    // nonaktifkan database gabungan untuk testing
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
    config(['setting.sebutan_desa' => 'Desa']);
});

test('export excel data desa', function () {
    // Arrange: Buat beberapa data test
    DataDesa::factory()->count(3)->create();

    // Act: Export data desa
    Excel::fake();

    $response = $this->get('/data/data-desa/export-excel');

    // Assert: Periksa status response
    $response->assertSuccessful();
});

test('export excel local database', function () {
    // Arrange: Buat beberapa data test
    DataDesa::factory()->count(5)->create();

    // Act: Buat instance export untuk database lokal
    $export = new ExportDataDesa(false, []);
    $collection = $export->collection();

    // Assert: Periksa data collection
    expect($collection->count())->toBe(DataDesa::count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export excel database gabungan active', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    // Buat beberapa data test lokal
    DataDesa::factory()->count(3)->create();

    // Act: Export dengan mode database gabungan
    Excel::fake();

    $response = $this->get('/data/data-desa/export-excel');

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
});

test('export data desa gabungan mode constructor', function () {
    // Act: Buat instance export dengan mode gabungan
    $export = new ExportDataDesa(true, []);

    // Assert: Instance export harus berhasil dibuat
    expect($export)->toBeInstanceOf(ExportDataDesa::class)
        ->and(method_exists($export, 'collection'))->toBeTrue();
});

test('export headings', function () {
    // Act: Buat instance export
    $export = new ExportDataDesa(false, []);
    $headings = $export->headings();

    // Assert: Periksa headings
    $expectedHeadings = [
        'ID',
        'Kode Desa',
        'Nama Desa',
        'Sebutan Desa',
        'Website',
        'Luas Wilayah (km²)',
        'Tanggal Dibuat',
        'Tanggal Diperbarui',
    ];

    expect($headings)->toBe($expectedHeadings);
});

test('export mapping', function () {
    // Arrange: Buat data test
    $dataDesa = DataDesa::factory()->create([
        'nama' => 'Test Desa',
        'desa_id' => '12345',
        'sebutan_desa' => 'Desa',
    ]);

    // Act: Buat instance export dan test mapping
    $export = new ExportDataDesa(false, []);
    $mappedData = $export->map($dataDesa);

    // Assert: Periksa struktur data yang dimapping
    expect($mappedData)->toBeArray()
        ->and($mappedData[0])->toBe($dataDesa->id)
        ->and($mappedData[1])->toBe('12345')
        ->and($mappedData[2])->toBe('Test Desa')
        ->and($mappedData[3])->toBe('Desa');
});

test('export styles', function () {
    // Act: Buat instance export
    $export = new ExportDataDesa(false, []);

    // Buat mock worksheet
    $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);

    // Assert: Method ada dan mengembalikan array styles
    $styles = $export->styles($worksheet);
    expect($styles)->toBeArray();
});

// =============================================================================
// EDGE CASES TESTS
// =============================================================================

test('export data desa with empty database', function () {
    // Arrange: Pastikan tidak ada data
    DataDesa::query()->delete();

    // Act: Buat instance export
    $export = new ExportDataDesa(false, []);
    $collection = $export->collection();

    // Assert: Collection harus kosong
    expect($collection)->toHaveCount(0)
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export data desa with special characters in nama', function () {
    // Arrange: Clean data first
    DataDesa::query()->delete();
    
    // Buat data dengan karakter khusus
    DataDesa::factory()->create([
        'nama' => 'Desa Test & Special <Characters> "Quotes"',
        'desa_id' => 'special_123',
    ]);

    // Act: Buat instance export
    $export = new ExportDataDesa(false, []);
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Data harus tetap ter-export dengan benar
    expect($collection)->toHaveCount(1)
        ->and($mappedData[2])->toBe('Desa Test & Special <Characters> "Quotes"');
});

test('export data desa with null values', function () {
    // Arrange: Clean data first
    DataDesa::query()->delete();
    
    // Buat data dengan nilai null
    DataDesa::factory()->create([
        'website' => null,
        'luas_wilayah' => null,
        'sebutan_desa' => null,
    ]);

    // Act: Buat instance export dan map data
    $export = new ExportDataDesa(false, []);
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Null values harus di-handle dengan benar
    expect($mappedData[4])->toBe('') // website
        ->and($mappedData[5])->toBe(0); // luas_wilayah default to 0
});

test('export data desa with very long nama', function () {
    // Arrange: Clean data first
    DataDesa::query()->delete();
    
    // Buat data dengan nama sangat panjang (max 255 chars untuk kolom nama)
    $longName = str_repeat('Test ', 50); // 250 chars
    DataDesa::factory()->create([
        'nama' => substr($longName, 0, 250),
    ]);

    // Act: Buat instance export
    $export = new ExportDataDesa(false, []);
    $collection = $export->collection();

    // Assert: Data panjang harus tetap ter-export
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->nama)->toBe(substr($longName, 0, 250));
});

test('export data desa with unicode characters', function () {
    // Arrange: Clean data first
    DataDesa::query()->delete();
    
    // Buat data dengan karakter unicode
    DataDesa::factory()->create([
        'nama' => 'Desa Cékér Ménténg',
    ]);

    // Act: Buat instance export
    $export = new ExportDataDesa(false, []);
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Unicode characters harus tetap terjaga
    expect($mappedData[2])->toBe('Desa Cékér Ménténg');
});

test('export data desa with custom sebutan_desa config', function () {
    // Arrange: Buat data dan set custom config
    config(['setting.sebutan_desa' => 'Kelurahan']);
    DataDesa::factory()->create();

    // Act: Buat instance export
    $export = new ExportDataDesa(false, []);
    $headings = $export->headings();

    // Assert: Headings harus menggunakan custom sebutan
    expect($headings[2])->toBe('Nama Kelurahan')
        ->and($headings[3])->toBe('Sebutan Kelurahan');
});

test('export data desa gabungan with empty api response', function () {
    // Arrange: Aktifkan mode gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    // Act: Buat instance export mode gabungan
    $export = new ExportDataDesa(true, []);

    // Assert: Instance harus bisa dibuat meskipun API kosong
    expect($export)->toBeInstanceOf(ExportDataDesa::class);
});

test('export data desa with large dataset performance', function () {
    // Arrange: Clean data first
    DataDesa::query()->delete();
    
    // Buat data dalam jumlah besar
    $startTime = microtime(true);
    DataDesa::factory()->count(100)->create();

    // Act: Export data
    $export = new ExportDataDesa(false, []);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export harus selesai dalam waktu yang wajar (< 2 detik)
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(100)
        ->and($executionTime)->toBeLessThan(2);
});

test('export data desa mapping with dates', function () {
    // Arrange: Buat data dengan tanggal spesifik
    $dataDesa = DataDesa::factory()->create([
        'created_at' => '2024-01-15 10:30:00',
        'updated_at' => '2024-06-20 14:45:00',
    ]);

    // Act: Buat instance export dan map
    $export = new ExportDataDesa(false, []);
    $mappedData = $export->map($dataDesa);

    // Assert: Format tanggal harus benar
    expect($mappedData[6])->toBe('15/01/2024 10:30:00')
        ->and($mappedData[7])->toBe('20/06/2024 14:45:00');
});

test('export data desa with zero luas_wilayah', function () {
    // Arrange: Buat data dengan luas_wilayah = 0
    DataDesa::factory()->create([
        'luas_wilayah' => 0,
    ]);

    // Act: Buat instance export
    $export = new ExportDataDesa(false, []);
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Luas wilayah 0 harus tetap ter-export
    expect($mappedData[5])->toBe(0);
});

test('export data desa styles returns correct structure', function () {
    // Arrange: Buat instance export
    $export = new ExportDataDesa(false, []);
    $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);

    // Act: Get styles
    $styles = $export->styles($worksheet);

    // Assert: Styles harus memiliki struktur yang benar
    expect($styles)->toHaveKey(1)
        ->and($styles[1])->toHaveKey('font')
        ->and($styles[1]['font'])->toHaveKey('bold')
        ->and($styles[1]['font']['bold'])->toBeTrue();
});
