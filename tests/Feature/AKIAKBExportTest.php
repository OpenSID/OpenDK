<?php

use App\Exports\ExportAKIAKB;
use App\Models\AkiAkb;
use App\Models\DataDesa;
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

test('export excel aki akb', function () {
    // Arrange: Bersihkan data dan buat data test baru
    AkiAkb::query()->delete();

    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test AKI AKB'
    ]);

    AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'aki' => 5,
        'akb' => 3,
        'bulan' => 1,
        'tahun' => 2024,
    ]);

    AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'aki' => 2,
        'akb' => 1,
        'bulan' => 2,
        'tahun' => 2024,
    ]);

    // Act: Export AKI AKB
    Excel::fake();

    $response = $this->get('/data/aki-akb/export-excel');

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();

    // Periksa bahwa data tersedia
    expect(AkiAkb::count())->toBe(2);
});

test('export aki akb headings', function () {
    // Arrange: Buat instance export
    $export = new ExportAKIAKB();

    // Act: Ambil headings
    $headings = $export->headings();

    // Assert: Periksa struktur headings
    $expectedHeadings = [
        'ID',
        'Nama Desa',
        'Kode Desa',
        'Jumlah AKI',
        'Jumlah AKB',
        'Bulan',
        'Tahun',
        'Tanggal Dibuat',
        'Tanggal Diperbarui',
    ];

    expect($headings)->toBe($expectedHeadings);
});

test('export aki akb data', function () {
    // Arrange: Bersihkan data dan buat data test baru
    AkiAkb::query()->delete();

    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test Export'
    ]);

    $akiAkb = AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'aki' => 7,
        'akb' => 4,
        'bulan' => 3,
        'tahun' => 2024,
    ]);

    // Act: Buat export
    $export = new ExportAKIAKB();
    $collection = $export->collection();

    // Assert: Periksa data yang diekspor
    expect($collection)->toHaveCount(1);

    $exportedData = $collection->first();
    expect($exportedData->id)->toBe($akiAkb->id)
        ->and($exportedData->desa->nama)->toBe('Desa Test Export')
        ->and($exportedData->aki)->toBe(7)
        ->and($exportedData->akb)->toBe(4)
        ->and($exportedData->bulan)->toBe(3)
        ->and($exportedData->tahun)->toBe(2024);
});

test('export aki akb mapping', function () {
    // Arrange: Bersihkan data dan buat data test
    AkiAkb::query()->delete();

    $desa = DataDesa::factory()->create([
        'nama' => 'Desa Test Mapping'
    ]);

    $akiAkb = AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'aki' => 6,
        'akb' => 2,
        'bulan' => 4,
        'tahun' => 2024,
    ]);

    // Act: Test mapping
    $export = new ExportAKIAKB();
    $mappedData = $export->map($akiAkb);

    // Assert: Periksa format mapping
    expect($mappedData[0])->toBe($akiAkb->id)
        ->and($mappedData[1])->toBe('Desa Test Mapping')
        ->and($mappedData[2])->toBe($desa->desa_id)
        ->and($mappedData[3])->toBe(6)
        ->and($mappedData[4])->toBe(2)
        ->and($mappedData[5])->toBe('April') // months_list()[4] = 'April'
        ->and($mappedData[6])->toBe(2024);
});

test('export aki akb no data', function () {
    // Arrange: Pastikan tidak ada data AKI AKB
    AkiAkb::query()->delete();

    // Act: Export ketika tidak ada data
    $export = new ExportAKIAKB();
    $collection = $export->collection();

    // Assert: Periksa bahwa collection kosong
    expect($collection)->toHaveCount(0);
});
