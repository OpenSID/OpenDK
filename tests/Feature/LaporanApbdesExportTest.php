<?php

use App\Exports\ExportLaporanApbdes;
use App\Models\DataDesa;
use App\Models\LaporanApbdes;
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
});

test('export excel laporan apbdes', function () {
    // Arrange: Buat beberapa data test
    $desa = DataDesa::factory()->create();
    LaporanApbdes::factory()->count(3)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export laporan apbdes
    Excel::fake();

    $response = $this->get('/data/laporan-apbdes/export-excel');

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
});

test('export excel laporan apbdes with desa filter', function () {
    // Arrange: Buat data test dengan beberapa desa
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    LaporanApbdes::factory()->count(2)->create([
        'desa_id' => $desa1->desa_id
    ]);
    LaporanApbdes::factory()->count(3)->create([
        'desa_id' => $desa2->desa_id
    ]);

    // Act: Export dengan filter desa
    Excel::fake();

    $response = $this->get('/data/laporan-apbdes/export-excel?desa_id=' . $desa1->desa_id);

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
});

test('export laporan apbdes local database no filter', function () {
    // Arrange: Buat data test
    $desa = DataDesa::factory()->create();
    LaporanApbdes::factory()->count(5)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Buat instance export tanpa filter
    $export = new ExportLaporanApbdes();
    $collection = $export->collection();

    // Assert: Periksa data collection
    expect($collection->count())->toBe(LaporanApbdes::count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export laporan apbdes local database with desa filter', function () {
    // Arrange: Buat data test dengan beberapa desa
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    LaporanApbdes::factory()->count(2)->create([
        'desa_id' => $desa1->desa_id
    ]);
    LaporanApbdes::factory()->count(3)->create([
        'desa_id' => $desa2->desa_id
    ]);

    // Act: Buat instance export dengan filter desa
    $export = new ExportLaporanApbdes(['desa_id' => $desa1->desa_id]);
    $collection = $export->collection();

    // Assert: Periksa data collection yang terfilter
    $expectedCount = LaporanApbdes::where('desa_id', $desa1->desa_id)->count();
    expect($collection->count())->toBe($expectedCount)
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export laporan apbdes with semua filter', function () {
    // Arrange: Buat data test
    $desa = DataDesa::factory()->create();
    LaporanApbdes::factory()->count(4)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Buat instance export dengan filter 'Semua'
    $export = new ExportLaporanApbdes(['desa_id' => 'Semua']);
    $collection = $export->collection();

    // Assert: Periksa bahwa semua data dikembalikan
    expect($collection->count())->toBe(LaporanApbdes::count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
