<?php

use App\Services\DesaService;
use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    // Clean up database before each test
    DataDesa::query()->delete();    
    
    // Create some sample data desa for testing
    DataDesa::factory()->count(5)->create();
    
    // Set up setting aplikasi to not use database gabungan by default
    SettingAplikasi::firstOrCreate([
        'key' => 'sinkronisasi_database_gabungan'
    ], ['value' => '0']);
});

it('can instantiate desa service', function () {
    $service = new DesaService();
    
    expect($service)->toBeInstanceOf(DesaService::class);
});

it('can get list of desa', function () {
    $service = new DesaService();
    
    $desaList = $service->listDesa();
    
    expect($desaList)->toHaveCount(5);
});

it('can get list of desa with all parameter', function () {
    $service = new DesaService();
    
    $desaListWithAll = $service->listDesa(true);
    
    expect($desaListWithAll)->toBeIterable();
    expect($desaListWithAll)->toHaveCount(5);
});

it('can get specific desa by slug when not using database gabungan', function () {
    $existingDesa = DataDesa::factory()->create(['nama' => 'Test Desa']);
    
    // Set up setting aplikasi to not use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '0']);
    
    $service = new DesaService();
    
    // We'll test the fallback behavior when not using database gabungan
    $desa = $service->dataDesa($existingDesa->nama);
    
    expect($desa)->not->toBeNull();
    expect($desa->nama)->toBe($existingDesa->nama);
});

it('can get specific desa by slug when using database gabungan', function () {
    $existingDesa = DataDesa::factory()->create(['nama' => 'Test Desa']);
    
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    // Mock API response
    $apiResponse = [
        'data' => [
            [
                'id' => 1,
                'attributes' => [
                    'kode_desa' => $existingDesa->id_desa,
                    'nama_desa' => $existingDesa->nama,
                    'sebutan_desa' => 'Desa',
                    'website' => 'https://example.com',
                    'luas_wilayah' => '1000',
                    'path' => '/test/path'
                ]
            ]
        ]
    ];
    
    Http::fake([
        'https://api.example.com/api/v1/wilayah/desa*' => Http::response($apiResponse, 200)
    ]);
    
    $service = new DesaService();
    
    $desa = $service->dataDesa($existingDesa->nama);
    
    expect($desa)->not->toBeNull();
    expect($desa->nama)->toBe($existingDesa->nama);
});

it('can get desa by kode', function () {
    $existingDesa = DataDesa::factory()->create(['id_desa' => 'TEST001']);
    
    $service = new DesaService();
    
    $desa = $service->getDesaByKode('TEST001');
    
    expect($desa)->not->toBeNull();
    expect($desa->id_desa)->toBe('TEST001');
});

it('returns null when desa by kode not found', function () {
    $service = new DesaService();
    
    $desa = $service->getDesaByKode('NONEXISTENT');
    
    expect($desa)->toBeNull();
});

it('can get jumlah desa', function () {
    $service = new DesaService();
    
    $jumlah = $service->jumlahDesa();
    
    expect($jumlah)->toBeInt();
    expect($jumlah)->toBeGreaterThanOrEqual(0);
});

it('can get path desa list', function () {
    $service = new DesaService();
    
    $pathDesaList = $service->listPathDesa();
    
    expect($pathDesaList)->toBeIterable();
});

it('can call desa method with filters', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    $service = new DesaService();
    
    $filteredDesa = $service->desa(['test_filter' => 'test_value']);
    
    expect($filteredDesa)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

it('handles empty results gracefully', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    $service = new DesaService();
    
    $emptyFilters = $service->desa([]);
    
    expect($emptyFilters)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

it('caches list desa when using database gabungan', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    // Clear cache first
    Cache::forget('listDesa');
    
    // Mock API response
    $apiResponse = [
        'data' => [
            [
                'id' => 1,
                'attributes' => [
                    'kode_desa' => 'TEST001',
                    'nama_desa' => 'Test Desa',
                    'sebutan_desa' => 'Desa',
                    'website' => 'https://example.com',
                    'luas_wilayah' => '1000',
                    'path' => '/test/path'
                ]
            ]
        ]
    ];
    
    Http::fake([
        'https://api.example.com/api/v1/wilayah/desa*' => Http::response($apiResponse, 200)
    ]);
    
    $service = new DesaService();
    
    // First call should hit the API
    $firstCall = $service->listDesa();
    
    // Second call should use cache
    $secondCall = $service->listDesa();
    
    expect($firstCall)->toEqual($secondCall);
});

it('returns cached list desa when available', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    // Set up cache with the correct structure
    $cachedData = collect([
        (object) [
            'desa_id' => 'CACHED001',
            'kode_desa' => 'CACHED001',
            'nama' => 'Cached Desa',
            'sebutan_desa' => 'Desa',
            'website' => 'https://cached.com',
            'luas_wilayah' => '2000',
            'path' => '/cached/path'
        ]
    ]);
    
    Cache::put('listDesa', $cachedData, 60 * 60 * 24);
    
    $service = new DesaService();
    $desaList = $service->listDesa();
    
    expect($desaList)->toEqual($cachedData);
});


it('transforms API response correctly', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    // Mock API response
    $apiResponse = [
        'data' => [
            [
                'id' => 1,
                'attributes' => [
                    'kode_desa' => 'TRANSFORM001',
                    'nama_desa' => 'Transform Test',
                    'sebutan_desa' => 'Kelurahan',
                    'website' => 'https://transform.com',
                    'luas_wilayah' => '1500',
                    'path' => '/transform/path'
                ]
            ]
        ]
    ];
    
    Http::fake([
        'https://api.example.com/api/v1/wilayah/desa*' => Http::response($apiResponse, 200)
    ]);
    
    $service = new DesaService();
    $desaList = $service->desa();
    
    expect($desaList)->toHaveCount(1);
    
    $desa = $desaList->first();
    expect($desa->desa_id)->toBe('TRANSFORM001');
    expect($desa->kode_desa)->toBe('TRANSFORM001');
    expect($desa->nama)->toBe('Transform Test');
    expect($desa->sebutan_desa)->toBe('Kelurahan');
    expect($desa->website)->toBe('https://transform.com');
    expect($desa->luas_wilayah)->toBe('1500');
    expect($desa->path)->toBe('/transform/path');
});

it('handles null values in API response', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    // Mock API response with null values
    $apiResponse = [
        'data' => [
            [
                'id' => 1,
                'attributes' => [
                    'kode_desa' => 'NULL001',
                    'nama_desa' => 'Null Test',
                    'sebutan_desa' => null,
                    'website' => null,
                    'luas_wilayah' => null,
                    'path' => null
                ]
            ]
        ]
    ];
    
    Http::fake([
        'https://api.example.com/api/v1/wilayah/desa*' => Http::response($apiResponse, 200)
    ]);
    
    $service = new DesaService();
    $desaList = $service->desa();
    
    expect($desaList)->toHaveCount(1);
    
    $desa = $desaList->first();
    expect($desa->desa_id)->toBe('NULL001');
    expect($desa->kode_desa)->toBe('NULL001');
    expect($desa->nama)->toBe('Null Test');
    expect($desa->sebutan_desa)->toBeNull();
    expect($desa->website)->toBeNull();
    expect($desa->luas_wilayah)->toBeNull();
    expect($desa->path)->toBeNull();
});

it('can get jumlah desa with filters', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    // Mock API response for jumlah desa
    $apiResponse = [
        'data' => [],
        'meta' => [
            'pagination' => [
                'total' => 10
            ]
        ]
    ];
    
    Http::fake([
        'https://api.example.com/api/v1/desa*' => Http::response($apiResponse, 200)
    ]);
    
    $service = new DesaService();
    $jumlah = $service->jumlahDesa(['filter[test]' => 'value']);
    
    expect($jumlah)->toBe(10);
});

it('returns 0 when jumlah desa API fails', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    // Mock API error response
    Http::fake([
        'https://api.example.com/api/v1/desa*' => Http::response(['error' => 'API Error'], 500)
    ]);
    
    $service = new DesaService();
    $jumlah = $service->jumlahDesa();
    
    expect($jumlah)->toBe(0);
});

it('handles malformed API response for jumlah desa', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    // Mock malformed API response
    $apiResponse = [
        'data' => [],
        'meta' => []
    ];
    
    Http::fake([
        'https://api.example.com/api/v1/desa*' => Http::response($apiResponse, 200)
    ]);
    
    $service = new DesaService();
    $jumlah = $service->jumlahDesa();
    
    expect($jumlah)->toBe(0);
});

it('can get path desa list when using database gabungan', function () {
    // Set up setting aplikasi to use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '1']);
    SettingAplikasi::updateOrCreate(['key' => 'api_server_database_gabungan'], ['value' => 'https://api.example.com']);
    SettingAplikasi::updateOrCreate(['key' => 'api_key_database_gabungan'], ['value' => 'test-key']);
    
    // Clear cache first
    Cache::forget('listDesa');
    
    // Mock API response
    $apiResponse = [
        'data' => [
            [
                'id' => 1,
                'attributes' => [
                    'kode_desa' => 'PATH001',
                    'nama_desa' => 'Path Test',
                    'sebutan_desa' => 'Desa',
                    'website' => 'https://path.com',
                    'luas_wilayah' => '1000',
                    'path' => '/path/test'
                ]
            ]
        ]
    ];
    
    Http::fake([
        'https://api.example.com/api/v1/wilayah/desa*' => Http::response($apiResponse, 200)
    ]);
    
    $service = new DesaService();
    $pathDesaList = $service->listPathDesa();
    
    expect($pathDesaList)->toBeIterable();
    expect($pathDesaList)->toHaveCount(1);
});

it('can get path desa list when not using database gabungan', function () {
    // Set up setting aplikasi to not use database gabungan
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->update(['value' => '0']);
    
    // Create a desa with path
    DataDesa::factory()->create(['path' => '/test/path']);
    DataDesa::factory()->create(['path' => null]);
    
    $service = new DesaService();
    $pathDesaList = $service->listPathDesa();
    
    expect($pathDesaList)->toBeIterable();
    expect($pathDesaList)->toHaveCount(1);
    expect($pathDesaList->first()->path)->toBe('/test/path');
});