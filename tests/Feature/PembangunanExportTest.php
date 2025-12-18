<?php

use App\Exports\ExportPembangunan;
use App\Models\DataDesa;
use App\Models\Pembangunan;
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

test('export excel pembangunan', function () {
    // Arrange: Buat beberapa data test
    $desa = DataDesa::factory()->create();
    Pembangunan::factory()->count(3)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export pembangunan
    Excel::fake();

    $response = $this->get('/data/pembangunan/export-excel');

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
});

test('export excel pembangunan with desa filter', function () {
    // Arrange: Buat data test dengan beberapa desa
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    Pembangunan::factory()->count(2)->create([
        'desa_id' => $desa1->desa_id
    ]);
    Pembangunan::factory()->count(3)->create([
        'desa_id' => $desa2->desa_id
    ]);

    // Act: Export dengan filter desa
    Excel::fake();

    $response = $this->get('/data/pembangunan/export-excel?desa_id=' . $desa1->desa_id);

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
});

test('export pembangunan local database no filter', function () {
    // Arrange: Buat data test
    $desa = DataDesa::factory()->create();
    Pembangunan::factory()->count(5)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Buat instance export tanpa filter
    $export = new ExportPembangunan();
    $collection = $export->collection();

    // Assert: Periksa data collection
    expect($collection->count())->toBe(Pembangunan::count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export pembangunan local database with desa filter', function () {
    // Arrange: Buat data test dengan beberapa desa
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    Pembangunan::factory()->count(2)->create([
        'desa_id' => $desa1->desa_id
    ]);
    Pembangunan::factory()->count(3)->create([
        'desa_id' => $desa2->desa_id
    ]);

    // Act: Buat instance export dengan filter desa
    $export = new ExportPembangunan(['desa_id' => $desa1->desa_id]);
    $collection = $export->collection();

    // Assert: Periksa data collection yang terfilter
    $expectedCount = Pembangunan::where('desa_id', $desa1->desa_id)->count();
    expect($collection->count())->toBe($expectedCount)
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export pembangunan with semua filter', function () {
    // Arrange: Buat data test
    $desa = DataDesa::factory()->create();
    Pembangunan::factory()->count(4)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Buat instance export dengan filter 'Semua'
    $export = new ExportPembangunan(['desa_id' => 'Semua']);
    $collection = $export->collection();

    // Assert: Periksa bahwa semua data dikembalikan
    expect($collection->count())->toBe(Pembangunan::count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
