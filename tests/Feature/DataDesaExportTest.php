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
