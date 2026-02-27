<?php

use App\Exports\ExportImunisasi;
use App\Models\DataDesa;
use App\Models\Imunisasi;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Maatwebsite\Excel\Facades\Excel;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware();

    // Menonaktifkan database gabungan untuk testing
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
});

test('export excel imunisasi', function () {
    // Arrange: Bersihkan data dan buat data test baru
    Imunisasi::query()->delete();

    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test Imunisasi'
    ]);

    Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 85,
        'bulan' => 1,
        'tahun' => 2024,
    ]);

    Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 92,
        'bulan' => 2,
        'tahun' => 2024,
    ]);

    // Act: Export Imunisasi
    Excel::fake();

    $response = $this->get('/data/imunisasi/export-excel');

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
    expect(Imunisasi::count())->toBe(2);
});

test('export imunisasi headings', function () {
    // Arrange: Buat instance export
    $export = new ExportImunisasi();

    // Act: Ambil headings
    $headings = $export->headings();

    // Assert: Periksa struktur headings
    $expectedHeadings = [
        'ID',
        'Nama Desa',
        'Kode Desa',
        'Cakupan Imunisasi',
        'Bulan',
        'Tahun',
        'Tanggal Dibuat',
        'Tanggal Diperbarui',
    ];

    expect($headings)->toBe($expectedHeadings);
});

test('export imunisasi data', function () {
    // Arrange: Bersihkan data dan buat data test baru
    Imunisasi::query()->delete();

    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test Export'
    ]);

    $imunisasi = Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 90,
        'bulan' => 3,
        'tahun' => 2024,
    ]);

    // Act: Buat export
    $export = new ExportImunisasi();
    $collection = $export->collection();

    // Assert: Periksa data yang diekspor
    expect($collection)->toHaveCount(1);

    $exportedData = $collection->first();
    expect($exportedData->id)->toBe($imunisasi->id)
        ->and($exportedData->desa->nama)->toBe('Desa Test Export')
        ->and($exportedData->cakupan_imunisasi)->toBe(90)
        ->and($exportedData->bulan)->toBe(3)
        ->and($exportedData->tahun)->toBe(2024);
});

test('export imunisasi mapping', function () {
    // Arrange: Bersihkan data dan buat data test
    Imunisasi::query()->delete();

    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test Mapping'
    ]);

    $imunisasi = Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 88,
        'bulan' => 4,
        'tahun' => 2024,
    ]);

    // Act: Test mapping
    $export = new ExportImunisasi();
    $mappedData = $export->map($imunisasi);

    // Assert: Periksa format mapping
    expect($mappedData[0])->toBe($imunisasi->id)
        ->and($mappedData[1])->toBe('Desa Test Mapping')
        ->and($mappedData[2])->toBe($desa->desa_id)
        ->and($mappedData[3])->toEqual(88)
        ->and($mappedData[4])->toBe('April')
        ->and($mappedData[5])->toEqual(2024);
});

test('export imunisasi no data', function () {
    // Arrange: Pastikan tidak ada data Imunisasi
    Imunisasi::query()->delete();

    // Act: Export ketika tidak ada data
    $export = new ExportImunisasi();
    $collection = $export->collection();

    // Assert: Periksa bahwa collection kosong
    expect($collection)->toHaveCount(0);
});

// =============================================================================
// VALIDATION TESTS
// =============================================================================

test('export imunisasi with invalid cakupan_imunisasi negative value', function () {
    // Arrange: Buat data dengan cakupan negatif
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    $imunisasi = Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => -10,
        'bulan' => 1,
        'tahun' => 2024,
    ]);

    // Act: Export tetap harus berhasil
    $export = new ExportImunisasi();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Data negatif tetap ter-export (validasi di layer lain)
    expect($collection)->toHaveCount(1)
        ->and($mappedData[3])->toBe(-10);
});

test('export imunisasi with cakupan_imunisasi above 100', function () {
    // Arrange: Buat data dengan cakupan > 100
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 150,
        'bulan' => 2,
        'tahun' => 2024,
    ]);

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();

    // Assert: Data > 100 tetap ter-export
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->cakupan_imunisasi)->toBe(150);
});

test('export imunisasi with cakupan_imunisasi zero', function () {
    // Arrange: Buat data dengan cakupan 0
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 0,
        'bulan' => 3,
        'tahun' => 2024,
    ]);

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Cakupan 0 harus tetap ter-export
    expect($collection)->toHaveCount(1)
        ->and($mappedData[3])->toBe(0);
});

test('export imunisasi with invalid bulan value', function () {
    // Arrange: Buat data dengan bulan tidak valid (13)
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 80,
        'bulan' => 13,
        'tahun' => 2024,
    ]);

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Bulan tidak valid harus di-handle
    expect($collection)->toHaveCount(1)
        ->and($mappedData[4])->toBe('Tidak Diketahui');
});

test('export imunisasi with bulan value zero', function () {
    // Arrange: Buat data dengan bulan = 0
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 75,
        'bulan' => 0,
        'tahun' => 2024,
    ]);

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Bulan 0 harus di-handle
    expect($collection)->toHaveCount(1)
        ->and($mappedData[4])->toBe('Tidak Diketahui');
});

test('export imunisasi with all valid months', function () {
    // Arrange: Buat data untuk setiap bulan valid
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    foreach ($months as $monthNum => $monthName) {
        Imunisasi::factory()->create([
            'desa_id' => $desa->desa_id,
            'cakupan_imunisasi' => 80,
            'bulan' => $monthNum,
            'tahun' => 2024,
        ]);
    }

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();

    // Assert: Semua bulan harus ter-map dengan benar
    expect($collection)->toHaveCount(12);

    $export = new ExportImunisasi();
    foreach ($collection as $index => $data) {
        $mappedData = $export->map($data);
        $expectedMonth = array_values($months)[$index];
        expect($mappedData[4])->toBe($expectedMonth);
    }
});

test('export imunisasi with invalid tahun value', function () {
    // Arrange: Buat data dengan tahun negatif
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 85,
        'bulan' => 1,
        'tahun' => -1,
    ]);

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Tahun negatif tetap ter-export
    expect($collection)->toHaveCount(1)
        ->and($mappedData[5])->toBe(-1);
});

test('export imunisasi with null desa relationship', function () {
    // Arrange: Buat data dengan desa_id yang tidak berelasi
    Imunisasi::query()->delete();

    // Buat imunisasi dengan desa_id yang tidak berelasi
    // Create with a non-existent desa_id to test null relationship handling
    $imunisasi = Imunisasi::create([
        'desa_id' => '9999999999999', // Non-existent desa_id
        'cakupan_imunisasi' => 90,
        'bulan' => 1,
        'tahun' => 2024,
    ]);

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Desa tidak ditemukan harus di-handle
    expect($collection)->toHaveCount(1)
        ->and($mappedData[1])->toBe('Tidak Diketahui')
        ->and($mappedData[2])->toBe('9999999999999');
});

test('export imunisasi with null created_at updated_at', function () {
    // Arrange: Buat data dengan timestamps null
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    $imunisasi = Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 88,
        'bulan' => 1,
        'tahun' => 2024,
        'created_at' => null,
        'updated_at' => null,
    ]);

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Null timestamps harus di-handle
    expect($collection)->toHaveCount(1)
        ->and($mappedData[6])->toBe('')
        ->and($mappedData[7])->toBe('');
});

test('export imunisasi styles returns correct structure', function () {
    // Arrange: Buat instance export
    $export = new ExportImunisasi();
    $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);

    // Act: Get styles
    $styles = $export->styles($worksheet);

    // Assert: Styles harus memiliki struktur yang benar
    expect($styles)->toBeArray()
        ->and($styles)->toHaveKey(1)
        ->and($styles[1])->toHaveKey('font')
        ->and($styles[1]['font'])->toHaveKey('bold')
        ->and($styles[1]['font']['bold'])->toBeTrue();
});

test('export imunisasi with decimal cakupan_imunisasi', function () {
    // Arrange: Buat data dengan cakupan desimal
    // Note: Database column is integer, so decimal will be rounded
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 87.5, // Will be stored as 88 (rounded)
        'bulan' => 1,
        'tahun' => 2024,
    ]);

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Cakupan desimal akan dibulatkan ke integer terdekat
    expect($collection)->toHaveCount(1)
        ->and($mappedData[3])->toBe(88); // Rounded from 87.5
});

test('export imunisasi with very large tahun value', function () {
    // Arrange: Buat data dengan tahun sangat besar
    Imunisasi::query()->delete();
    $desa = DataDesa::factory()->create();

    Imunisasi::factory()->create([
        'desa_id' => $desa->desa_id,
        'cakupan_imunisasi' => 80,
        'bulan' => 1,
        'tahun' => 9999,
    ]);

    // Act: Export
    $export = new ExportImunisasi();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Tahun besar harus tetap ter-export
    expect($collection)->toHaveCount(1)
        ->and($mappedData[5])->toBe(9999);
});
