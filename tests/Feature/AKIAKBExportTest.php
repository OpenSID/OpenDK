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

// =============================================================================
// FILTER TESTS
// =============================================================================

test('export aki akb with desa_id filter', function () {
    // Arrange: Buat data dengan beberapa desa
    AkiAkb::query()->delete();
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    AkiAkb::factory()->count(3)->create([
        'desa_id' => $desa1->desa_id,
        'bulan' => 1,
        'tahun' => 2024,
    ]);
    AkiAkb::factory()->count(2)->create([
        'desa_id' => $desa2->desa_id,
        'bulan' => 2,
        'tahun' => 2024,
    ]);

    // Act: Export dengan filter desa_id
    $export = new ExportAKIAKB(['desa_id' => $desa1->desa_id]);
    $collection = $export->collection();

    // Assert: Hanya data desa1 yang ter-export
    expect($collection)->toHaveCount(3)
        ->and($collection->every(fn($item) => $item->desa_id === '111'))->toBeTrue();
});

test('export aki akb with bulan filter', function () {
    // Arrange: Buat data dengan berbagai bulan
    AkiAkb::query()->delete();
    $desa = DataDesa::factory()->create();

    AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'bulan' => 1,
        'tahun' => 2024,
    ]);
    AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'bulan' => 2,
        'tahun' => 2024,
    ]);
    AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'bulan' => 3,
        'tahun' => 2024,
    ]);

    // Act: Export dengan filter bulan
    $export = new ExportAKIAKB(['bulan' => 2]);
    $collection = $export->collection();

    // Assert: Hanya data bulan 2 yang ter-export
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->bulan)->toBe(2);
});

test('export aki akb with tahun filter', function () {
    // Arrange: Buat data dengan berbagai tahun
    AkiAkb::query()->delete();
    $desa = DataDesa::factory()->create();

    AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'bulan' => 1,
        'tahun' => 2023,
    ]);
    AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'bulan' => 2,
        'tahun' => 2024,
    ]);
    AkiAkb::factory()->create([
        'desa_id' => $desa->desa_id,
        'bulan' => 3,
        'tahun' => 2025,
    ]);

    // Act: Export dengan filter tahun
    $export = new ExportAKIAKB(['tahun' => 2024]);
    $collection = $export->collection();

    // Assert: Hanya data tahun 2024 yang ter-export
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->tahun)->toBe(2024);
});

test('export aki akb with combined filters', function () {
    // Arrange: Buat data dengan berbagai kombinasi
    AkiAkb::query()->delete();
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    // Data untuk desa1, bulan 1, tahun 2024
    AkiAkb::factory()->create([
        'desa_id' => $desa1->desa_id,
        'bulan' => 1,
        'tahun' => 2024,
    ]);

    // Data untuk desa1, bulan 2, tahun 2024 (tidak match semua filter)
    AkiAkb::factory()->create([
        'desa_id' => $desa1->desa_id,
        'bulan' => 2,
        'tahun' => 2024,
    ]);

    // Data untuk desa2, bulan 1, tahun 2024 (tidak match semua filter)
    AkiAkb::factory()->create([
        'desa_id' => $desa2->desa_id,
        'bulan' => 1,
        'tahun' => 2024,
    ]);

    // Data untuk desa1, bulan 1, tahun 2023 (tidak match semua filter)
    AkiAkb::factory()->create([
        'desa_id' => $desa1->desa_id,
        'bulan' => 1,
        'tahun' => 2023,
    ]);

    // Act: Export dengan kombinasi filter
    $export = new ExportAKIAKB([
        'desa_id' => $desa1->desa_id,
        'bulan' => 1,
        'tahun' => 2024
    ]);
    $collection = $export->collection();

    // Assert: Hanya data yang match semua filter yang ter-export
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->desa_id)->toBe('111')
        ->and($collection->first()->bulan)->toBe(1)
        ->and($collection->first()->tahun)->toBe(2024);
});

test('export aki akb dengan filter Semua for desa_id', function () {
    // Arrange: Buat data dengan beberapa desa
    AkiAkb::query()->delete();
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    AkiAkb::factory()->count(2)->create([
        'desa_id' => $desa1->desa_id,
    ]);
    AkiAkb::factory()->count(3)->create([
        'desa_id' => $desa2->desa_id,
    ]);

    // Act: Export dengan filter 'Semua'
    $export = new ExportAKIAKB(['desa_id' => 'Semua']);
    $collection = $export->collection();

    // Assert: Semua data harus ter-export
    expect($collection)->toHaveCount(5);
});

test('export aki akb dengan filter Semua for bulan', function () {
    // Arrange: Buat data dengan berbagai bulan
    AkiAkb::query()->delete();
    $desa = DataDesa::factory()->create();

    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'bulan' => 1]);
    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'bulan' => 2]);
    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'bulan' => 3]);

    // Act: Export dengan filter 'Semua' untuk bulan
    $export = new ExportAKIAKB(['bulan' => 'Semua']);
    $collection = $export->collection();

    // Assert: Semua data harus ter-export
    expect($collection)->toHaveCount(3);
});

test('export aki akb dengan filter Semua for tahun', function () {
    // Arrange: Buat data dengan berbagai tahun
    AkiAkb::query()->delete();
    $desa = DataDesa::factory()->create();

    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'tahun' => 2023]);
    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'tahun' => 2024]);
    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'tahun' => 2025]);

    // Act: Export dengan filter 'Semua' untuk tahun
    $export = new ExportAKIAKB(['tahun' => 'Semua']);
    $collection = $export->collection();

    // Assert: Semua data harus ter-export
    expect($collection)->toHaveCount(3);
});

test('export aki akb with empty filter', function () {
    // Arrange: Buat data
    AkiAkb::query()->delete();
    $desa = DataDesa::factory()->create();
    AkiAkb::factory()->count(4)->create([
        'desa_id' => $desa->desa_id,
    ]);

    // Act: Export dengan filter kosong
    $export = new ExportAKIAKB([]);
    $collection = $export->collection();

    // Assert: Semua data harus ter-export
    expect($collection)->toHaveCount(4);
});

test('export aki akb with non_existent desa_id filter', function () {
    // Arrange: Buat data
    AkiAkb::query()->delete();
    $desa = DataDesa::factory()->create();
    AkiAkb::factory()->count(2)->create([
        'desa_id' => $desa->desa_id,
    ]);

    // Act: Export dengan filter desa_id yang tidak ada
    $export = new ExportAKIAKB(['desa_id' => 'non_existent']);
    $collection = $export->collection();

    // Assert: Tidak ada data yang ter-export
    expect($collection)->toHaveCount(0);
});

test('export aki akb with non_existent bulan filter', function () {
    // Arrange: Buat data dengan bulan 1-3
    AkiAkb::query()->delete();
    $desa = DataDesa::factory()->create();
    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'bulan' => 1]);
    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'bulan' => 2]);

    // Act: Export dengan filter bulan yang tidak ada
    $export = new ExportAKIAKB(['bulan' => 99]);
    $collection = $export->collection();

    // Assert: Tidak ada data yang ter-export
    expect($collection)->toHaveCount(0);
});

test('export aki akb with non_existent tahun filter', function () {
    // Arrange: Buat data dengan tahun 2023-2024
    AkiAkb::query()->delete();
    $desa = DataDesa::factory()->create();
    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'tahun' => 2023]);
    AkiAkb::factory()->create(['desa_id' => $desa->desa_id, 'tahun' => 2024]);

    // Act: Export dengan filter tahun yang tidak ada
    $export = new ExportAKIAKB(['tahun' => 1990]);
    $collection = $export->collection();

    // Assert: Tidak ada data yang ter-export
    expect($collection)->toHaveCount(0);
});

test('export aki akb filter preserves relationships', function () {
    // Arrange: Buat data dengan relasi desa
    AkiAkb::query()->delete();
    $desa1 = DataDesa::factory()->create(['desa_id' => '111', 'nama' => 'Desa Filter Test']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222', 'nama' => 'Desa Lain']);

    AkiAkb::factory()->create([
        'desa_id' => $desa1->desa_id,
        'aki' => 5,
        'akb' => 3,
    ]);
    AkiAkb::factory()->create([
        'desa_id' => $desa2->desa_id,
        'aki' => 2,
        'akb' => 1,
    ]);

    // Act: Export dengan filter
    $export = new ExportAKIAKB(['desa_id' => $desa1->desa_id]);
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Relasi desa harus tetap ter-load
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->desa)->not->toBeNull()
        ->and($collection->first()->desa->nama)->toBe('Desa Filter Test')
        ->and($mappedData[1])->toBe('Desa Filter Test');
});
