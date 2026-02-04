<?php

use App\Exports\ExportProgramBantuan;
use App\Models\DataDesa;
use App\Models\Program;
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

test('export excel program bantuan', function () {
    // Arrange: Buat beberapa data test
    $desa = DataDesa::factory()->create();
    Program::factory()->count(3)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export program bantuan
    Excel::fake();

    $response = $this->get('/data/program-bantuan/export-excel');

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
});

test('export excel program bantuan with desa filter', function () {
    // Arrange: Buat data test dengan beberapa desa
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    Program::factory()->count(2)->create([
        'desa_id' => $desa1->desa_id
    ]);
    Program::factory()->count(3)->create([
        'desa_id' => $desa2->desa_id
    ]);

    // Act: Export dengan filter desa
    Excel::fake();

    $response = $this->get('/data/program-bantuan/export-excel?desa_id=' . $desa1->desa_id);

    // Assert: Periksa bahwa export berhasil
    $response->assertSuccessful();
});

test('export program bantuan local database no filter', function () {
    // Arrange: Buat data test
    $desa = DataDesa::factory()->create();
    Program::factory()->count(5)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Buat instance export tanpa filter
    $export = new ExportProgramBantuan();
    $collection = $export->collection();

    // Assert: Periksa data collection
    expect($collection->count())->toBe(Program::count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export program bantuan local database with desa filter', function () {
    // Arrange: Buat data test dengan beberapa desa
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    Program::factory()->count(2)->create([
        'desa_id' => $desa1->desa_id
    ]);
    Program::factory()->count(3)->create([
        'desa_id' => $desa2->desa_id
    ]);

    // Act: Buat instance export dengan filter desa
    $export = new ExportProgramBantuan(['desa_id' => $desa1->desa_id]);
    $collection = $export->collection();

    // Assert: Periksa data collection yang terfilter
    $expectedCount = Program::where('desa_id', $desa1->desa_id)->count();
    expect($collection->count())->toBe($expectedCount)
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export program bantuan with semua filter', function () {
    // Arrange: Buat data test
    $desa = DataDesa::factory()->create();
    Program::factory()->count(4)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Buat instance export dengan filter 'Semua'
    $export = new ExportProgramBantuan(['desa_id' => 'Semua']);
    $collection = $export->collection();

    // Assert: Periksa bahwa semua data dikembalikan
    expect($collection->count())->toBe(Program::count())
        ->and($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
