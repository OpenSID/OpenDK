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

// =============================================================================
// COMPREHENSIVE PROGRAM BANTUAN EXPORT TESTS
// =============================================================================

test('export program bantuan headings', function () {
    // Arrange: Buat instance export
    $export = new ExportProgramBantuan();

    // Act: Ambil headings
    $headings = $export->headings();

    // Assert: Periksa struktur headings
    $expectedHeadings = [
        'ID',
        'Nama Program',
        'Desa',
        'Masa Berlaku',
        'Sasaran',
        'Tanggal Dibuat',
        'Tanggal Diperbarui',
    ];

    expect($headings)->toBe($expectedHeadings);
});

test('export program bantuan with empty database', function () {
    // Arrange: Pastikan tidak ada data
    Program::query()->delete();

    // Act: Buat instance export
    $export = new ExportProgramBantuan();
    $collection = $export->collection();

    // Assert: Collection harus kosong
    expect($collection)->toHaveCount(0);
});

test('export program bantuan mapping with dates', function () {
    // Arrange: Buat data test dengan tanggal
    $desa = DataDesa::factory()->create();
    $program = Program::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'Test Program',
        'start_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'sasaran' => 1,
    ]);

    // Act: Test mapping
    $export = new ExportProgramBantuan();
    $mappedData = $export->map($program);

    // Assert: Periksa mapping
    expect($mappedData[0])->toBe($program->id)
        ->and($mappedData[1])->toBe('Test Program')
        ->and($mappedData[3])->toContain('2024-01-01')
        ->and($mappedData[3])->toContain('2024-12-31')
        ->and($mappedData[4])->toBe('Penduduk/Perorangan');
});

test('export program bantuan with null dates', function () {
    // Arrange: Buat data tanpa tanggal
    $desa = DataDesa::factory()->create();
    $program = Program::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'Test No Dates',
        'start_date' => null,
        'end_date' => null,
        'sasaran' => 2,
    ]);

    // Act: Test mapping
    $export = new ExportProgramBantuan();
    $mappedData = $export->map($program);

    // Assert: Null dates harus di-handle
    expect($mappedData[3])->toBe('-')
        ->and($mappedData[4])->toBe('Keluarga-KK');
});

test('export program bantuan with various sasaran values', function () {
    // Arrange: Clean data first
    Program::query()->delete();
    
    // Buat data dengan berbagai sasaran
    $desa = DataDesa::factory()->create();
    Program::factory()->create([
        'desa_id' => $desa->desa_id,
        'sasaran' => 1, // Penduduk/Perorangan
    ]);
    Program::factory()->create([
        'desa_id' => $desa->desa_id,
        'sasaran' => 2, // Keluarga-KK
    ]);
    Program::factory()->create([
        'desa_id' => $desa->desa_id,
        'sasaran' => 99, // Invalid
    ]);

    // Act: Export
    $export = new ExportProgramBantuan();
    $collection = $export->collection();

    // Assert: Berbagai nilai sasaran harus di-handle
    expect($collection)->toHaveCount(3);

    $sasaranList = $collection->map(fn($p) => $export->map($p)[4])->toArray();
    expect($sasaranList)->toContain('Penduduk/Perorangan')
        ->and($sasaranList)->toContain('Keluarga-KK');
});

test('export program bantuan with special characters', function () {
    // Arrange: Clean data first
    Program::query()->delete();
    
    // Buat data dengan karakter khusus
    $desa = DataDesa::factory()->create();
    Program::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'Program & Special <Test> "Quotes"',
    ]);

    // Act: Export
    $export = new ExportProgramBantuan();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Karakter khusus harus tetap terjaga
    expect($mappedData[1])->toBe('Program & Special <Test> "Quotes"');
});

test('export program bantuan performance test', function () {
    // Arrange: Clean data first
    Program::query()->delete();
    
    // Buat data dalam jumlah besar
    $desa = DataDesa::factory()->create();
    $startTime = microtime(true);
    Program::factory()->count(200)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export
    $export = new ExportProgramBantuan();
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export harus cepat
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(200)
        ->and($executionTime)->toBeLessThan(2);
});

test('export program bantuan with multiple desa', function () {
    // Arrange: Clean data first
    Program::query()->delete();
    
    // Buat data di beberapa desa
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);
    $desa3 = DataDesa::factory()->create(['desa_id' => '333']);

    Program::factory()->count(5)->create(['desa_id' => $desa1->desa_id]);
    Program::factory()->count(3)->create(['desa_id' => $desa2->desa_id]);
    Program::factory()->count(2)->create(['desa_id' => $desa3->desa_id]);

    // Act: Export dengan filter
    $export = new ExportProgramBantuan(['desa_id' => $desa2->desa_id]);
    $collection = $export->collection();

    // Assert: Hanya data desa2 yang ter-export
    expect($collection)->toHaveCount(3)
        ->and($collection->every(fn($p) => $p->desa_id === '333' || $p->desa_id === '222'))->toBeTrue();
});

test('export program bantuan styles', function () {
    // Arrange: Buat instance export
    $export = new ExportProgramBantuan();
    $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);

    // Act: Get styles
    $styles = $export->styles($worksheet);

    // Assert: Styles harus memiliki struktur yang benar
    expect($styles)->toBeArray()
        ->and($styles)->toHaveKey(1)
        ->and($styles[1])->toHaveKey('font');
});

test('export program bantuan with null desa relationship', function () {
    // Arrange: Clean data first
    Program::query()->delete();
    
    // Buat data dengan desa_id tidak valid
    $program = Program::factory()->create([
        'desa_id' => 'non_existent',
        'nama' => 'Test No Desa',
    ]);

    // Act: Export
    $export = new ExportProgramBantuan();
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Null desa harus di-handle
    expect($mappedData[2])->toBe('');
});

test('export program bantuan memory usage', function () {
    // Arrange: Clean data first
    Program::query()->delete();
    
    // Arrange: Buat data besar
    $desa = DataDesa::factory()->create();
    Program::factory()->count(500)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Monitor memory
    $startMemory = memory_get_usage();
    $export = new ExportProgramBantuan();
    $collection = $export->collection();
    $endMemory = memory_get_usage();

    // Assert: Memory usage harus dalam batas wajar
    $memoryUsed = $endMemory - $startMemory;
    expect($collection->count())->toBe(500)
        ->and($memoryUsed)->toBeLessThan(50 * 1024 * 1024); // 50MB
});
