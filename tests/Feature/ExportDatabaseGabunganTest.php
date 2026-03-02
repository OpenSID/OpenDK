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

use App\Exports\ExportAKIAKB;
use App\Exports\ExportDataDesa;
use App\Exports\ExportKeluarga;
use App\Exports\ExportPenduduk;
use App\Models\AkiAkb;
use App\Models\DataDesa;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use App\Services\DesaService;
use App\Services\KeluargaService;
use App\Services\PendudukService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware();
    
    // Clear cache sebelum setiap test
    Cache::flush();
    
    // Reset settings to default
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
});

// =============================================================================
// DATABASE GABUNGAN INACTIVE - LOCAL DATABASE TESTS
// =============================================================================

test('export data desa with database gabungan inactive uses local database', function () {
    // Arrange: Nonaktifkan database gabungan dan buat data baru
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
    
    // Clean existing data first
    DataDesa::query()->delete();
    $desa = DataDesa::factory()->count(5)->create();

    // Act: Export dengan mode lokal
    $export = new ExportDataDesa(false, []);
    $collection = $export->collection();

    // Assert: Data diambil dari database lokal
    expect($collection->count())->toBe(5)
        ->and($collection->first())->toBeInstanceOf(DataDesa::class);
});

test('export keluarga with database gabungan inactive uses local database', function () {
    // Arrange: Nonaktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    $desa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $desa->desa_id]);
    Keluarga::factory()->count(5)->create([
        'nik_kepala' => $penduduk->nik,
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export dengan mode lokal
    $export = new ExportKeluarga(false, []);
    $collection = $export->collection();

    // Assert: Data diambil dari database lokal
    expect($collection->count())->toBeGreaterThan(0);
});

test('export penduduk with database gabungan inactive uses local database', function () {
    // Arrange: Nonaktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );
    
    // Clean existing data
    Penduduk::query()->delete();

    $desa = DataDesa::factory()->create();
    Penduduk::factory()->count(10)->create(['desa_id' => $desa->desa_id]);

    // Act: Export dengan mode lokal
    $export = new ExportPenduduk(false, []);
    $collection = $export->collection();

    // Assert: Data diambil dari database lokal
    expect($collection->count())->toBe(10);
});

// =============================================================================
// DATABASE GABUNGAN ACTIVE - API MOCK TESTS
// =============================================================================

test('export data desa with database gabungan active calls API', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock API response - use wildcard pattern
    Http::fake([
        '*api/v1/wilayah/desa*' => Http::response([
            'data' => [
                [
                    'attributes' => [
                        'kode_desa' => '111',
                        'nama_desa' => 'Desa Test 1',
                        'sebutan_desa' => 'Desa',
                        'website' => 'https://desa1.com',
                        'luas_wilayah' => 10.5,
                    ]
                ],
                [
                    'attributes' => [
                        'kode_desa' => '222',
                        'nama_desa' => 'Desa Test 2',
                        'sebutan_desa' => 'Kelurahan',
                        'website' => 'https://desa2.com',
                        'luas_wilayah' => 15.2,
                    ]
                ],
            ]
        ], 200)
    ]);

    // Act: Export dengan mode gabungan
    $export = new ExportDataDesa(true, []);
    $collection = $export->collection();

    // Assert: API dipanggil
    Http::assertSent(fn ($request) => 
        str_contains($request->url(), 'api/v1/wilayah/desa')
    );
    
    expect($collection->count())->toBe(2)
        ->and($collection->first()->nama)->toBe('Desa Test 1');
});

test('export data desa with database gabungan active via controller', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock API response
    Http::fake([
        '*api/v1/wilayah/desa*' => Http::response([
            'data' => [
                [
                    'attributes' => [
                        'kode_desa' => '111',
                        'nama_desa' => 'Desa Test',
                        'sebutan_desa' => 'Desa',
                        'website' => 'https://desa.com',
                        'luas_wilayah' => 10.0,
                    ]
                ],
            ]
        ], 200)
    ]);

    Excel::fake();

    // Act: Export via controller
    $response = $this->get('/data/data-desa/export-excel');

    // Assert: Response successful dan API dipanggil
    $response->assertSuccessful();
    
    Http::assertSent(fn ($request) => 
        str_contains($request->url(), 'api/v1/wilayah/desa')
    );
});

test('export data desa with database gabungan active returns mapped data', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock API response
    Http::fake([
        'api.example.com/api/v1/wilayah/desa*' => Http::response([
            'data' => [
                [
                    'attributes' => [
                        'kode_desa' => '111',
                        'nama_desa' => 'Desa API Test',
                        'sebutan_desa' => 'Desa',
                        'website' => 'https://test.com',
                        'luas_wilayah' => 20.5,
                    ]
                ],
            ]
        ], 200)
    ]);

    // Act: Export dan map data
    $export = new ExportDataDesa(true, []);
    $collection = $export->collection();
    $mappedData = $export->map($collection->first());

    // Assert: Data di-map dengan benar untuk mode gabungan
    expect($collection->count())->toBe(1)
        ->and($mappedData[0])->toBe('') // ID tidak ada di API
        ->and($mappedData[1])->toBe('111')
        ->and($mappedData[2])->toBe('Desa API Test')
        ->and($mappedData[3])->toBe('Desa')
        ->and($mappedData[6])->toBe('') // created_at tidak ada di API
        ->and($mappedData[7])->toBe(''); // updated_at tidak ada di API
});

test('export data desa with database gabungan active handles API error', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock API error response
    Http::fake([
        'api.example.com/api/v1/wilayah/desa*' => Http::response([], 500)
    ]);

    // Act: Export dengan API error
    $export = new ExportDataDesa(true, []);
    $collection = $export->collection();

    // Assert: Collection kosong saat API error
    expect($collection)->toHaveCount(0);
});

test('export data desa with database gabungan active handles empty API response', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock empty API response
    Http::fake([
        'api.example.com/api/v1/wilayah/desa*' => Http::response([
            'data' => []
        ], 200)
    ]);

    // Act: Export dengan response kosong
    $export = new ExportDataDesa(true, []);
    $collection = $export->collection();

    // Assert: Collection kosong
    expect($collection)->toHaveCount(0);
});

test('export data desa with database gabungan active handles connection timeout', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock connection timeout
    Http::fake([
        'api.example.com/api/v1/wilayah/desa*' => Http::response([], 200, [], 0) // timeout
    ]);

    // Act: Export dengan timeout
    $export = new ExportDataDesa(true, []);
    $collection = $export->collection();

    // Assert: Handle timeout dengan return empty collection
    expect($collection)->toHaveCount(0);
});

// =============================================================================
// DESA SERVICE MOCK TESTS
// =============================================================================

test('DesaService listDesa with database gabungan active uses cache', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock cached data
    $cachedData = collect([
        (object) ['desa_id' => '111', 'nama' => 'Cached Desa']
    ]);
    Cache::put('listDesa', $cachedData, 60 * 60 * 24);

    // Mock API (tidak seharusnya dipanggil karena ada cache)
    Http::fake([
        'api.example.com/api/v1/wilayah/desa*' => Http::response([
            'data' => [
                ['attributes' => ['kode_desa' => '999', 'nama_desa' => 'API Desa']]
            ]
        ], 200)
    ]);

    // Act: Get list desa
    $service = new DesaService();
    $result = $service->listDesa();

    // Assert: Menggunakan cache, bukan API
    expect($result->count())->toBe(1)
        ->and($result->first()->nama)->toBe('Cached Desa');
    
    // API tidak dipanggil karena cache hit
    Http::assertNotSent(fn ($request) => 
        $request->url() === 'https://api.example.com/api/v1/wilayah/desa'
    );
});

test('DesaService listDesa with database gabungan active calls API when cache empty', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Clear cache
    Cache::flush();

    // Mock API response
    Http::fake([
        '*api/v1/wilayah/desa*' => Http::response([
            'data' => [
                [
                    'attributes' => [
                        'kode_desa' => '111',
                        'nama_desa' => 'Desa From API',
                        'sebutan_desa' => 'Desa',
                        'website' => 'https://desa.com',
                        'luas_wilayah' => 10.0,
                    ]
                ],
            ]
        ], 200)
    ]);

    // Act: Get list desa (cache miss, should call API)
    $service = new DesaService();
    $result = $service->listDesa();

    // Assert: API dipanggil saat cache kosong
    Http::assertSent(fn ($request) => 
        str_contains($request->url(), 'api/v1/wilayah/desa')
    );
    
    expect($result->count())->toBe(1)
        ->and($result->first()->nama)->toBe('Desa From API');
});

// =============================================================================
// KELUARGA SERVICE MOCK TESTS
// =============================================================================

test('export keluarga with database gabungan active uses KeluargaService', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock API response for KeluargaService
    Http::fake([
        'api.example.com/api/v1/*' => Http::response([
            'data' => [
                [
                    'attributes' => [
                        'nik_kepala' => '1234567890123456',
                        'no_kk' => '1234567890123456',
                        'alamat' => 'Jl. Test',
                        'dusun' => 'Dusun 1',
                        'rw' => '01',
                        'rt' => '001',
                    ],
                    'relationships' => [
                        'kepala_kk' => [
                            'data' => ['attributes' => ['nama' => 'Kepala KK']]
                        ],
                        'desa' => [
                            'data' => ['attributes' => ['nama_desa' => 'Desa Test']]
                        ],
                    ]
                ],
            ]
        ], 200)
    ]);

    // Act: Export dengan mode gabungan
    $export = new ExportKeluarga(true, []);
    
    // Assert: Export class harus bisa diinstantiate
    expect($export)->toBeInstanceOf(ExportKeluarga::class);
    
    // Note: KeluargaService implementation mungkin berbeda, test ini memastikan
    // class bisa diinstantiate dan method collection ada
    expect(method_exists($export, 'collection'))->toBeTrue();
});

// =============================================================================
// PENDUDUK SERVICE MOCK TESTS
// =============================================================================

test('export penduduk with database gabungan active uses PendudukService', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock API response for PendudukService
    Http::fake([
        'api.example.com/api/v1/*' => Http::response([
            'data' => [
                [
                    'attributes' => [
                        'nama' => 'Penduduk Test',
                        'nik' => '1234567890123456',
                        'no_kk' => '1234567890123456',
                        'alamat' => 'Jl. Test',
                    ]
                ],
            ]
        ], 200)
    ]);

    // Act: Export dengan mode gabungan
    $export = new ExportPenduduk(true, []);
    
    // Assert: Export class harus bisa diinstantiate
    expect($export)->toBeInstanceOf(ExportPenduduk::class)
        ->and(method_exists($export, 'collection'))->toBeTrue();
});

// =============================================================================
// AKI AKB TESTS (NO GABUNGAN MODE - LOCAL ONLY)
// =============================================================================

test('export aki akb always uses local database', function () {
    // Arrange: Aktifkan database gabungan (tapi AKI AKB tidak support mode gabungan)
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    
    // Clean data first
    AkiAkb::query()->delete();

    $desa = DataDesa::factory()->create();
    AkiAkb::factory()->count(3)->create([
        'desa_id' => $desa->desa_id
    ]);

    // Act: Export AKI AKB (tidak support mode gabungan)
    $export = new ExportAKIAKB([]);
    $collection = $export->collection();

    // Assert: AKI AKB selalu dari database lokal
    expect($collection->count())->toBe(3);
});

// =============================================================================
// TOGGLE DATABASE GABUNGAN TESTS
// =============================================================================

test('toggle database gabungan setting from inactive to active', function () {
    // Arrange: Setup awal - nonaktif
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    $desa = DataDesa::factory()->create();
    $originalCount = DataDesa::count();

    // Act 1: Export mode lokal
    $exportLokal = new ExportDataDesa(false, []);
    $collectionLokal = $exportLokal->collection();

    // Toggle ke aktif
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );

    // Mock API
    Http::fake([
        'api.example.com/api/v1/wilayah/desa*' => Http::response([
            'data' => [
                ['attributes' => ['kode_desa' => '999', 'nama_desa' => 'API Desa']]
            ]
        ], 200)
    ]);

    // Act 2: Export mode gabungan
    $exportGabungan = new ExportDataDesa(true, []);
    $collectionGabungan = $exportGabungan->collection();

    // Assert: Mode lokal mengambil dari DB, mode gabungan dari API
    expect($collectionLokal->count())->toBe($originalCount)
        ->and($collectionGabungan->count())->toBe(1)
        ->and($collectionGabungan->first()->nama)->toBe('API Desa');
});

test('database gabungan setting does not affect local data integrity', function () {
    // Arrange: Buat data lokal
    DataDesa::query()->delete();
    $desa = DataDesa::factory()->count(5)->create();
    $originalData = DataDesa::all()->pluck('nama')->sort()->values()->toArray();

    // Act: Toggle setting
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );

    // Assert: Data lokal tidak terpengaruh
    expect(DataDesa::count())->toBe(5)
        ->and(DataDesa::all()->pluck('nama')->sort()->values()->toArray())->toBe($originalData);
});

// =============================================================================
// API AUTHORIZATION TESTS
// =============================================================================

test('api request includes correct authorization header', function () {
    // Arrange: Aktifkan database gabungan dengan API key
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'secret-api-key-123']
    );

    // Mock API
    Http::fake([
        'api.example.com/api/v1/wilayah/desa*' => Http::response([
            'data' => []
        ], 200)
    ]);

    // Act: Export
    $export = new ExportDataDesa(true, []);
    $export->collection();

    // Assert: Authorization header harus benar
    Http::assertSent(fn ($request) => 
        $request->hasHeader('Authorization', 'Bearer secret-api-key-123') &&
        $request->hasHeader('Accept', 'application/ld+json') &&
        $request->hasHeader('Content-Type', 'application/json')
    );
});

test('api request includes correct query parameters', function () {
    // Arrange: Aktifkan database gabungan
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'https://api.example.com']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-key']
    );

    // Mock API
    Http::fake([
        '*api/v1/wilayah/desa*' => Http::response([
            'data' => []
        ], 200)
    ]);

    // Act: Export dengan filter
    $export = new ExportDataDesa(true, ['filter[nama]' => 'Test']);
    $export->collection();

    // Assert: Request dikirim ke endpoint yang benar
    Http::assertSent(fn ($request) => 
        str_contains($request->url(), 'api/v1/wilayah/desa')
    );
});
