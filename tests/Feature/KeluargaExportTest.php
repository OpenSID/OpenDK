<?php

use App\Exports\ExportKeluarga;
use App\Models\DataDesa;
use App\Models\Keluarga;
use App\Models\Penduduk;
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

test('export excel keluarga', function () {
    // Arrange: Buat beberapa data test
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
    Keluarga::factory()->count(3)->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export keluarga
    Excel::fake();

    $response = $this->get('/data/keluarga/export-excel');

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
});

test('export excel keluarga with desa filter', function () {
    // Arrange: Buat data test dengan beberapa desa
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    $penduduk1 = Penduduk::factory()->create(['desa_id' => $desa1->desa_id]);
    $penduduk2 = Penduduk::factory()->create(['desa_id' => $desa2->desa_id]);

    Keluarga::factory()->count(2)->create([
        'nik_kepala' => $penduduk1->nik,
        'desa_id' => $desa1->desa_id
    ]);
    Keluarga::factory()->count(3)->create([
        'nik_kepala' => $penduduk2->nik,
        'desa_id' => $desa2->desa_id
    ]);

    // Act: Export dengan filter desa
    Excel::fake();

    $response = $this->get('/data/keluarga/export-excel?desa=' . $desa1->desa_id);

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
});

test('export keluarga local database no filter', function () {
    // Arrange: Buat data test
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
    Keluarga::factory()->count(5)->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Buat instance export tanpa filter
    $export = new ExportKeluarga(false, []);
    $collection = $export->collection();

    // Assert: Periksa data collection
    expect($collection->count())->toBe(Keluarga::has('kepala_kk')->count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export keluarga local database with desa filter', function () {
    // Arrange: Buat data test dengan beberapa desa
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    $penduduk1 = Penduduk::factory()->create(['desa_id' => $desa1->desa_id]);
    $penduduk2 = Penduduk::factory()->create(['desa_id' => $desa2->desa_id]);

    Keluarga::factory()->count(2)->create([
        'nik_kepala' => $penduduk1->nik,
        'desa_id' => $desa1->desa_id
    ]);
    Keluarga::factory()->count(3)->create([
        'nik_kepala' => $penduduk2->nik,
        'desa_id' => $desa2->desa_id
    ]);

    // Act: Buat instance export dengan filter desa
    $export = new ExportKeluarga(false, ['desa' => $desa1->desa_id]);
    $collection = $export->collection();

    // Assert: Periksa data collection yang terfilter
    $expectedCount = Keluarga::has('kepala_kk')->where('desa_id', $desa1->desa_id)->count();
    expect($collection->count())->toBe($expectedCount)
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export keluarga with semua filter', function () {
    // Arrange: Buat data test
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
    Keluarga::factory()->count(4)->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Buat instance export dengan filter 'Semua'
    $export = new ExportKeluarga(false, ['desa' => 'Semua']);
    $collection = $export->collection();

    // Assert: Periksa bahwa semua data dikembalikan
    expect($collection->count())->toBe(Keluarga::has('kepala_kk')->count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
