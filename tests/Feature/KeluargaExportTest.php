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

// =============================================================================
// PERFORMANCE TESTS
// =============================================================================

test('export keluarga with small dataset performance', function () {
    // Arrange: Clean data first
    Keluarga::query()->delete();
    
    // Buat data dalam jumlah kecil
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
    $startTime = microtime(true);

    Keluarga::factory()->count(50)->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export data
    $export = new ExportKeluarga(false, []);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export harus selesai dalam waktu yang wajar (< 1 detik)
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(50)
        ->and($executionTime)->toBeLessThan(1);
});

test('export keluarga with medium dataset performance', function () {
    // Arrange: Clean data first
    Keluarga::query()->delete();
    
    // Buat data dalam jumlah medium
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
    $startTime = microtime(true);

    Keluarga::factory()->count(200)->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export data
    $export = new ExportKeluarga(false, []);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export harus selesai dalam waktu yang wajar (< 3 detik)
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(200)
        ->and($executionTime)->toBeLessThan(3);
});

test('export keluarga with large dataset performance', function () {
    // Arrange: Clean data first
    Keluarga::query()->delete();
    
    // Buat data dalam jumlah besar
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
    $startTime = microtime(true);

    Keluarga::factory()->count(500)->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export data
    $export = new ExportKeluarga(false, []);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export harus selesai dalam waktu yang wajar (< 5 detik)
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(500)
        ->and($executionTime)->toBeLessThan(5);
});

test('export keluarga with multiple desa performance', function () {
    // Arrange: Clean data first
    Keluarga::query()->delete();
    
    // Buat data di beberapa desa
    $startTime = microtime(true);
    $desas = DataDesa::factory()->count(5)->create();

    foreach ($desas as $desa) {
        $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
        Keluarga::factory()->count(100)->create([
            'nik_kepala' => $penduduk->nik,
            'desa_id' => $desa->desa_id
        ]);
    }

    // Act: Export semua data
    $export = new ExportKeluarga(false, []);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export harus selesai dalam waktu yang wajar
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(500)
        ->and($executionTime)->toBeLessThan(5);
});

test('export keluarga with filter performance', function () {
    // Arrange: Clean data first
    Keluarga::query()->delete();
    
    // Buat data dalam jumlah besar dengan filter
    $startTime = microtime(true);
    $desa1 = DataDesa::factory()->create(['desa_id' => '111']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '222']);

    $penduduk1 = Penduduk::factory()->create(['desa_id' => $desa1->desa_id]);
    $penduduk2 = Penduduk::factory()->create(['desa_id' => $desa2->desa_id]);

    Keluarga::factory()->count(300)->create([
        'nik_kepala' => $penduduk1->nik,
        'desa_id' => $desa1->desa_id
    ]);
    Keluarga::factory()->count(300)->create([
        'nik_kepala' => $penduduk2->nik,
        'desa_id' => $desa2->desa_id
    ]);

    // Act: Export dengan filter
    $export = new ExportKeluarga(false, ['desa' => $desa1->desa_id]);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export dengan filter harus lebih cepat (< 2 detik)
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(300)
        ->and($executionTime)->toBeLessThan(2);
});

test('export keluarga memory usage with large dataset', function () {
    // Arrange: Clean data first
    Keluarga::query()->delete();
    
    // Buat data dalam jumlah besar
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);

    Keluarga::factory()->count(1000)->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export data dan monitor memory
    $startMemory = memory_get_usage();
    $export = new ExportKeluarga(false, []);
    $collection = $export->collection();
    $endMemory = memory_get_usage();

    // Assert: Memory usage harus dalam batas wajar (< 50MB untuk export)
    $memoryUsed = $endMemory - $startMemory;
    expect($collection->count())->toBe(1000)
        ->and($memoryUsed)->toBeLessThan(50 * 1024 * 1024); // 50MB
});

test('export keluarga with eager loading performance', function () {
    // Arrange: Clean data first
    Keluarga::query()->delete();
    
    // Buat data dengan relasi
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->count(10)->create(['desa_id' => $desa->desa_id]);
    $startTime = microtime(true);

    foreach ($penduduk as $p) {
        Keluarga::factory()->create([
            'nik_kepala' => $p->nik,
            'desa_id' => $desa->desa_id
        ]);
    }

    // Act: Export dengan eager loading
    $export = new ExportKeluarga(false, []);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Eager loading harus efisien (< 1 detik)
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(10)
        ->and($executionTime)->toBeLessThan(1);

    // Assert: Relasi harus ter-load
    expect($collection->first()->kepala_kk)->not->toBeNull()
        ->and($collection->first()->desa)->not->toBeNull();
});

test('export keluarga repeated exports consistency', function () {
    // Arrange: Clean data first
    Keluarga::query()->delete();
    
    // Buat data test
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
    Keluarga::factory()->count(100)->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export berulang kali
    $startTime = microtime(true);
    $export1 = new ExportKeluarga(false, []);
    $collection1 = $export1->collection();

    $export2 = new ExportKeluarga(false, []);
    $collection2 = $export2->collection();

    $export3 = new ExportKeluarga(false, []);
    $collection3 = $export3->collection();
    $endTime = microtime(true);

    // Assert: Hasil export harus konsisten
    $executionTime = $endTime - $startTime;
    expect($collection1->count())->toBe($collection2->count())
        ->and($collection2->count())->toBe($collection3->count())
        ->and($collection1->count())->toBe(100)
        ->and($executionTime)->toBeLessThan(3);
});

test('export keluarga with complex data relationships', function () {
    // Arrange: Clean data first
    Keluarga::query()->delete();
    
    // Buat data dengan relasi kompleks
    $desa = DataDesa::factory()->create();
    $startTime = microtime(true);

    // Buat banyak penduduk dan keluarga
    for ($i = 0; $i < 50; $i++) {
        $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
        Keluarga::factory()->create([
            'nik_kepala' => $penduduk->nik,
            'desa_id' => $desa->desa_id
        ]);
    }

    // Act: Export dengan relasi kompleks
    $export = new ExportKeluarga(false, []);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export dengan relasi harus efisien
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(50)
        ->and($executionTime)->toBeLessThan(2);
});

test('export keluarga styles performance', function () {
    // Arrange: Buat instance export
    $export = new ExportKeluarga(false, []);
    $worksheet = $this->createMock(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class);

    // Act: Generate styles berulang kali
    $startTime = microtime(true);
    for ($i = 0; $i < 100; $i++) {
        $styles = $export->styles($worksheet);
    }
    $endTime = microtime(true);

    // Assert: Styles generation harus cepat
    $executionTime = $endTime - $startTime;
    expect($styles)->toBeArray()
        ->and($executionTime)->toBeLessThan(0.5);
});
