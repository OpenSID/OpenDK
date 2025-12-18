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
        ->and($mappedData[3])->toBe('88')
        ->and($mappedData[4])->toBe('April')
        ->and($mappedData[5])->toBe(2024);
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
