<?php

use App\Services\ApiService;
use App\Models\SettingAplikasi;
use App\Models\User;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// ApiService Testing
it('can instantiate api service', function () {
    // Create required settings
    SettingAplikasi::firstOrCreate(['key' => 'layanan_opendesa_token'], ['value' => 'test_token']);
    
    $service = new ApiService();
    
    expect($service)->toBeInstanceOf(ApiService::class);
});

it('can register customer successfully', function () {
    // Create required settings
    SettingAplikasi::firstOrCreate(['key' => 'layanan_opendesa_token'], ['value' => 'test_token']);
    
    // Create a user and authenticate
    $user = User::factory()->create();
    Auth::login($user);
    
    Http::fake([
        '*/api/v1/pelanggan/register-kecamatan' => Http::response([
            'id' => 1,
            'status' => 'active'
        ], 200)
    ]);

    // Mock storage for file attachment
    Storage::fake('local');
    Storage::put('test_file.pdf', 'fake file content');

    $service = new ApiService();
    $response = $service->register([
        'email' => 'test@example.com',
        'kecamatan_id' => '3201',
        'kontak_no_hp' => '08123456789',
        'kontak_nama' => 'Test Contact',
        'domain' => 'test.example.com',
        'status_langganan_id' => 1,
        'permohonan' => 'test_file.pdf'
    ]);
    
    expect($response)->toBeArray();
    expect($response['success'])->toBeTrue();
    expect($response['message'])->toBe('Pendaftaran pelanggan berhasil.');
    expect($response['data']['id'])->toBe(1);
});

it('handles registration failure', function () {
    // Create required settings
    SettingAplikasi::firstOrCreate(['key' => 'layanan_opendesa_token'], ['value' => 'test_token']);
    
    // Create a user and authenticate
    $user = User::factory()->create();
    Auth::login($user);
    
    Http::fake([
        '*/api/v1/pelanggan/register-kecamatan' => Http::response([
            'error' => 'Validation failed'
        ], 422)
    ]);

    // Mock storage for file attachment
    Storage::fake('local');
    Storage::put('test_file.pdf', 'fake file content');

    $service = new ApiService();
    $response = $service->register([
        'email' => 'invalid-email',
        'kecamatan_id' => '3201',
        'kontak_no_hp' => '08123456789',
        'kontak_nama' => 'Test Contact',
        'domain' => 'test.example.com',
        'status_langganan_id' => 1,
        'permohonan' => 'test_file.pdf'
    ]);
    
    expect($response)->toBeArray();
    expect($response['success'])->toBeFalse();
    expect($response['message'])->toBe('Pendaftaran pelanggan gagal.');
    expect($response['error']['error'])->toBe('Validation failed');
});

it('handles registration network error', function () {
    // Create required settings
    SettingAplikasi::firstOrCreate(['key' => 'layanan_opendesa_token'], ['value' => 'test_token']);
    
    // Create a user and authenticate
    $user = User::factory()->create();
    Auth::login($user);
    
    Http::fake([
        '*/api/v1/pelanggan/register-kecamatan' => Http::response('', 500)
    ]);

    // Mock storage for file attachment
    Storage::fake('local');
    Storage::put('test_file.pdf', 'fake file content');

    $service = new ApiService();
    $response = $service->register([
        'email' => 'test@example.com',
        'kecamatan_id' => '3201',
        'kontak_no_hp' => '08123456789',
        'kontak_nama' => 'Test Contact',
        'domain' => 'test.example.com',
        'status_langganan_id' => 1,
        'permohonan' => 'test_file.pdf'
    ]);
    
    expect($response)->toBeArray();
    expect($response['success'])->toBeFalse();
    expect($response['message'])->toBe('Pendaftaran pelanggan gagal.');    
});

it('can get registered customers', function () {
    // Create required settings
    SettingAplikasi::firstOrCreate(['key' => 'layanan_opendesa_token'], ['value' => 'test_token']);
    
    Http::fake([
        '*/api/v1/pelanggan/terdaftar-kecamatan' => Http::response([
            [
                'id' => 1,
                'email' => 'test1@example.com',
                'domain' => 'test1.example.com'
            ],
            [
                'id' => 2,
                'email' => 'test2@example.com',
                'domain' => 'test2.example.com'
            ]
        ], 200)
    ]);

    $service = new ApiService();
    $response = $service->terdaftar('3201');
    
    expect($response)->toBeArray();
    expect($response['success'])->toBeTrue();
    expect($response['message'])->toBe('Data berhasil diambil.');
    expect($response['data'])->toHaveCount(2);
    expect($response['data'][0]['email'])->toBe('test1@example.com');
});

it('handles get registered customers failure', function () {
    // Create required settings
    SettingAplikasi::firstOrCreate(['key' => 'layanan_opendesa_token'], ['value' => 'test_token']);
    
    Http::fake([
        '*/api/v1/pelanggan/terdaftar-kecamatan' => Http::response([
            'message' => 'Unauthorized access'
        ], 401)
    ]);

    $service = new ApiService();
    $response = $service->terdaftar('3201');
    
    expect($response)->toBeArray();
    expect($response['success'])->toBeFalse();
    expect($response['message'])->toBe('Unauthorized access');
    expect($response['data'])->toBe('{"message":"Unauthorized access"}');
});

it('can get registration form', function () {
    // Create required settings
    SettingAplikasi::firstOrCreate(['key' => 'layanan_opendesa_token'], ['value' => 'test_token']);
    
    Http::fake([
        '*/api/v1/pelanggan/form-register-kecamatan' => Http::response([
            'fields' => [
                'email' => ['type' => 'email', 'required' => true],
                'domain' => ['type' => 'text', 'required' => true],
                'kecamatan_id' => ['type' => 'select', 'required' => true]
            ]
        ], 200)
    ]);

    $service = new ApiService();
    $response = $service->getFormRegister();
    
    expect($response)->toBeArray();
    expect($response['success'])->toBeTrue();
    expect($response['message'])->toBe('Data berhasil diambil.');
    expect($response['data']['fields'])->toHaveKey('email');
    expect($response['data']['fields']['email']['type'])->toBe('email');
});

it('handles get registration form failure', function () {
    // Create required settings
    SettingAplikasi::firstOrCreate(['key' => 'layanan_opendesa_token'], ['value' => 'test_token']);
    
    Http::fake([
        '*/api/v1/pelanggan/form-register-kecamatan' => Http::response('', 500)
    ]);

    $service = new ApiService();
    $response = $service->getFormRegister();
    
    expect($response)->toBeArray();
    expect($response['success'])->toBeFalse();
    expect($response['message'])->toBe('Gagal mengambil data.');
    expect($response['status'])->toBe(500);
});

it('handles missing layanan_opendesa_token setting', function () {
    // Don't create required setting
    $service = new ApiService();
    
    // Should still instantiate but may have issues with API calls
    expect($service)->toBeInstanceOf(ApiService::class);
});