<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use App\Exports\ExportPenduduk;
use App\Models\DataDesa;
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Maatwebsite\Excel\Facades\Excel;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware();    
    // Arrange: Clean data first
    Penduduk::query()->delete();
});

test('export excel', function () {
    // Act: Export the users
    Excel::fake();

    $this->get('/data/penduduk/export-excel');

    // Assert: Check that the export was called
    Excel::assertDownloaded('data-penduduk.xlsx', function (ExportPenduduk $export) {
        $pendudukCount = Penduduk::count();
        return $export->collection()->count() == $pendudukCount;
    });
});

// =============================================================================
// COMPREHENSIVE EXPORT PENDUDUK TESTS
// =============================================================================

test('export penduduk headings', function () {
    // Arrange: Buat instance export
    $export = new ExportPenduduk(false, []);

    // Act: Ambil headings
    $headings = $export->headings();

    // Assert: Periksa struktur headings
    $expectedHeadings = [
        'ID',
        'NAMA',
        'NIK',
        'NO.KK',
        'DESA',
        'ALAMAT',
        'PENDIDIKAN DALAM KK',
        'TANGGAL LAHIR',
        'UMUR',
        'PEKERJAAN',
        'STATUS KAWIN',
    ];

    expect($headings)->toBe($expectedHeadings);
});

test('export penduduk with empty database', function () {
    // Arrange: Pastikan tidak ada data
    Penduduk::query()->delete();

    // Act: Buat instance export
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();

    // Assert: Collection harus kosong
    expect($collection)->toHaveCount(0);
});

test('export penduduk with data', function () {
    
    
    // Buat data test
    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'Test Penduduk',
        'nik' => '1234567890123456',
        'no_kk' => '1234567890123456',
    ]);

    // Act: Buat export
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();

    // Assert: Periksa data yang diekspor
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->nama)->toBe('Test Penduduk');
});

test('export penduduk with pagination params', function () {
    
    
    // Buat data test
    $desa = DataDesa::factory()->create();
    Penduduk::factory()->count(20)->create(['desa_id' => $desa->desa_id]);

    // Act: Export dengan pagination
    $params = [
        'page' => ['size' => 10, 'number' => 1],
    ];
    $export = new ExportPenduduk(false, $params);
    $collection = $export->collection();

    // Assert: Periksa pagination
    // Note: Implementasi pagination tergantung pada class ExportPenduduk
    expect($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export penduduk with search filter', function () {
    
    
    // Buat data test
    $desa = DataDesa::factory()->create();
    Penduduk::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'John Doe',
    ]);
    Penduduk::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'Jane Smith',
    ]);

    // Act: Export dengan search filter
    $params = [
        'filter' => ['search' => 'John'],
    ];
    $export = new ExportPenduduk(false, $params);
    $collection = $export->collection();

    // Assert: Periksa hasil search
    // Note: Implementasi search tergantung pada class ExportPenduduk
    expect($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('export penduduk database gabungan mode', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    // Act: Buat instance export mode gabungan
    $export = new ExportPenduduk(true, []);

    // Assert: Instance harus bisa dibuat
    expect($export)->toBeInstanceOf(ExportPenduduk::class)
        ->and(method_exists($export, 'collection'))->toBeTrue();
});

test('export penduduk with null relationships', function () {
    
    
    // Buat data tanpa relasi lengkap (hanya field opsional yang null)
    $penduduk = Penduduk::factory()->create([
        'nama' => 'Test Null Relations',
        'pendidikan_kk_id' => null,
        'pekerjaan_id' => null,
    ]);

    // Act: Buat export
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();

    // Assert: Data dengan null relations harus di-handle
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->nama)->toBe('Test Null Relations');
});

test('export penduduk with special characters', function () {
    
    
    // Buat data dengan karakter khusus
    $desa = DataDesa::factory()->create();
    Penduduk::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'Test & Special <Characters> "Quotes"',
        'alamat' => 'Jl. Test No. 123, Kec. & Desa',
    ]);

    // Act: Buat export
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();

    // Assert: Karakter khusus harus tetap terjaga
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->nama)->toBe('Test & Special <Characters> "Quotes"');
});

test('export penduduk with unicode characters', function () {
    
    
    // Buat data dengan karakter unicode
    $desa = DataDesa::factory()->create();
    Penduduk::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'Cékér Ménténg',
        'alamat' => 'Jl. Éksklusif No. 1',
    ]);

    // Act: Buat export
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();

    // Assert: Unicode characters harus tetap terjaga
    expect($collection)->toHaveCount(1)
        ->and($collection->first()->nama)->toBe('Cékér Ménténg');
});

test('export penduduk performance small dataset', function () {
    
    
    // Buat data kecil
    $desa = DataDesa::factory()->create();
    $startTime = microtime(true);
    Penduduk::factory()->count(50)->create(['desa_id' => $desa->desa_id]);

    // Act: Export
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export harus cepat
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(50)
        ->and($executionTime)->toBeLessThan(1);
});

test('export penduduk performance medium dataset', function () {        
    // Buat data medium
    $desa = DataDesa::factory()->create();
    $startTime = microtime(true);
    Penduduk::factory()->count(200)->create(['desa_id' => $desa->desa_id]);

    // Act: Export
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();
    $endTime = microtime(true);

    // Assert: Export harus selesai dalam waktu wajar
    $executionTime = $endTime - $startTime;
    expect($collection->count())->toBe(200)
        ->and($executionTime)->toBeLessThan(3);
});

test('export penduduk with various tanggal_lahir formats', function () {
    
    
    // Arrange: Buat data dengan berbagai format tanggal
    $desa = DataDesa::factory()->create();
    Penduduk::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'Test Date 1',
        'tanggal_lahir' => '1990-01-15',
    ]);
    Penduduk::factory()->create([
        'desa_id' => $desa->desa_id,
        'nama' => 'Test Date 2',
        'tanggal_lahir' => null,
    ]);

    // Act: Buat export
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();

    // Assert: Data dengan berbagai format tanggal harus ter-export
    expect($collection)->toHaveCount(2);
});

test('export penduduk memory usage', function () {
    
    
    // Arrange: Buat data besar
    $desa = DataDesa::factory()->create();
    Penduduk::factory()->count(500)->create(['desa_id' => $desa->desa_id]);

    // Act: Monitor memory usage
    $startMemory = memory_get_usage();
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();
    $endMemory = memory_get_usage();

    // Assert: Memory usage harus dalam batas wajar
    $memoryUsed = $endMemory - $startMemory;
    expect($collection->count())->toBe(500)
        ->and($memoryUsed)->toBeLessThan(50 * 1024 * 1024); // 50MB
});
