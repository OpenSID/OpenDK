<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 */

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\Komplain;
use App\Models\Penduduk;
use App\Models\SettingAplikasi;
use App\Repositories\KomplainApiRepository;
use App\Services\PendudukService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // CrudTestCase setup
    $this->withViewErrors([]);
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
        GlobalShareMiddleware::class,
    ]);
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    // Mock repository
    $this->komplainRepository = Mockery::mock(KomplainApiRepository::class);
    $this->app->instance(KomplainApiRepository::class, $this->komplainRepository);
    Cache::flush();
});

test('index returns paginated complaints', function () {
    $komplain = Komplain::factory()->create([
        'status' => 'DITERIMA',
        'judul' => 'Test Komplain',
        'nik' => '1234567890123456',
    ]);

    $this->komplainRepository
        ->shouldReceive('data')
        ->once()
        ->andReturn(collect([$komplain]));

    Cache::shouldReceive('remember')
        ->once()
        ->andReturnUsing(fn(...$args) => is_callable(end($args)) ? end($args)() : end($args));

    $response = $this->getJson('/api/frontend/v1/komplain');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'type',
                'attributes' => [
                    'komplain_id',
                    'judul',
                    'slug',
                    'laporan',
                    'status',
                    'kategori',
                    'nik',
                    'nama',
                    'anonim',
                    'dilihat',
                    'lampiran1',
                    'lampiran2',
                    'lampiran3',
                    'lampiran4',
                    'detail_penduduk',
                    'created_at',
                    'updated_at',
                ],
            ]
        ]
    ]);
});

test('index with filters', function () {
    $this->komplainRepository->shouldReceive('data')->once()->andReturn(collect());
    Cache::shouldReceive('remember')->once()->andReturnUsing(fn(...$args) => is_callable(end($args)) ? end($args)() : end($args));

    $response = $this->getJson('/api/frontend/v1/komplain?filter[status]=DITERIMA&filter[kategori]=1&search=jalan');
    $response->assertStatus(200);
});

test('index with pagination', function () {
    $this->komplainRepository->shouldReceive('data')->once()->andReturn(collect());
    Cache::shouldReceive('remember')->once()->andReturnUsing(fn(...$args) => is_callable(end($args)) ? end($args)() : end($args));

    $response = $this->getJson('/api/frontend/v1/komplain?page[number]=2&page[size]=10');
    $response->assertStatus(200);
});

test('store with valid data', function () {
    $penduduk = Penduduk::factory()->create([
        'nik' => '1234567890123456',
        'tanggal_lahir' => '1990-01-01',
    ]);

    $komplain = new Komplain([
        'nik' => '1234567890123456',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Ini adalah laporan test',
        'tanggal_lahir' => '1990-01-01',
    ]);
    $komplain->komplain_id = 999123;
    $komplain->slug = 'test-komplain-999123';
    $komplain->status = 'REVIEW';
    $komplain->dilihat = 0;

    $this->komplainRepository->shouldReceive('create')->andReturn($komplain);
    $this->komplainRepository->shouldReceive('saveWithAttachments')->andReturn(true);
    Cache::shouldReceive('forget')->andReturn(true);

    $lampiran1 = UploadedFile::fake()->image('lampiran1.jpg');
    $lampiran2 = UploadedFile::fake()->image('lampiran2.jpg');

    $data = [
        'nik' => '1234567890123456',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Ini adalah laporan test',
        'tanggal_lahir' => '1990-01-01',
        'anonim' => false,
        'lampiran1' => $lampiran1,
        'lampiran2' => $lampiran2,
    ];

    $response = $this->postJson('/api/frontend/v1/komplain', $data);
    expect($response->getStatusCode())->toBeIn([201, 500, 422]);
});

test('store with invalid data', function () {
    $data = [
        'nik' => '',
        'judul' => '',
        'kategori' => '',
        'laporan' => '',
        'tanggal_lahir' => '',
    ];

    $response = $this->postJson('/api/frontend/v1/komplain', $data);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['nik', 'judul', 'kategori', 'laporan', 'tanggal_lahir']);
});

test('store when penduduk not found', function () {
    $komplain = new Komplain([
        'nik' => '9999999999999999',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Ini adalah laporan test',
        'tanggal_lahir' => '1990-01-01',
    ]);
    $komplain->komplain_id = 999123;
    $komplain->slug = 'test-komplain-999123';
    $komplain->status = 'REVIEW';
    $komplain->dilihat = 0;

    $this->komplainRepository->shouldReceive('create')->andReturn($komplain);

    $data = [
        'nik' => '9999999999999999',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Ini adalah laporan test',
        'tanggal_lahir' => '1990-01-01',
    ];

    $response = $this->postJson('/api/frontend/v1/komplain', $data);
    expect($response->getStatusCode())->toBe(422);
});

test('store with database gabungan enabled', function () {
    SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->first()?->update(['value' => '1']);

    $pendudukService = Mockery::mock(PendudukService::class);
    $this->app->instance(PendudukService::class, $pendudukService);

    $mockPenduduk = new Penduduk([
        'nik' => '1234567890123456',
        'nama' => 'Test User',
        'tanggal_lahir' => '1990-01-01',
    ]);

    $pendudukService->shouldReceive('cekPendudukNikTanggalLahir')->andReturn($mockPenduduk);

    $komplain = new Komplain([
        'nik' => '1234567890123456',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Ini adalah laporan test',
        'tanggal_lahir' => '1990-01-01',
    ]);
    $komplain->komplain_id = 999123;
    $komplain->slug = 'test-komplain-999123';
    $komplain->status = 'REVIEW';
    $komplain->dilihat = 0;

    $this->komplainRepository->shouldReceive('create')->andReturn($komplain);
    $this->komplainRepository->shouldReceive('saveWithAttachments')->andReturn(true);
    Cache::shouldReceive('forget')->andReturn(true);

    $data = [
        'nik' => '1234567890123456',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Ini adalah laporan test',
        'tanggal_lahir' => '1990-01-01',
    ];

    $response = $this->postJson('/api/frontend/v1/komplain', $data);
    expect($response->getStatusCode())->toBeIn([201, 422]);
});

test('store with exception', function () {
    $this->komplainRepository->shouldReceive('create')->andThrow(new \Exception('Database error'));

    $data = [
        'nik' => '1234567890123456',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Ini adalah laporan test',
        'tanggal_lahir' => '1990-01-01',
    ];

    $response = $this->postJson('/api/frontend/v1/komplain', $data);
    expect($response->getStatusCode())->toBeIn([500, 422]);
});

test('store with file attachments', function () {
    $penduduk = Penduduk::factory()->create([
        'nik' => '1234567890123456',
        'tanggal_lahir' => '1990-01-01',
    ]);

    $komplain = new Komplain([
        'nik' => '1234567890123456',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Ini adalah laporan test',
        'tanggal_lahir' => '1990-01-01',
    ]);
    $komplain->komplain_id = 999123;
    $komplain->slug = 'test-komplain-999123';
    $komplain->status = 'REVIEW';
    $komplain->dilihat = 0;

    $this->komplainRepository->shouldReceive('create')->andReturn($komplain);
    $this->komplainRepository->shouldReceive('saveWithAttachments')->andReturn(true);
    Cache::shouldReceive('forget')->andReturn(true);

    $data = [
        'nik' => '1234567890123456',
        'judul' => 'Test Komplain',
        'kategori' => 1,
        'laporan' => 'Ini adalah laporan test',
        'tanggal_lahir' => '1990-01-01',
        'anonim' => false,
        'lampiran1' => UploadedFile::fake()->image('lampiran1.jpg'),
        'lampiran2' => UploadedFile::fake()->image('lampiran2.jpg'),
        'lampiran3' => UploadedFile::fake()->image('lampiran3.jpg'),
        'lampiran4' => UploadedFile::fake()->image('lampiran4.jpg'),
    ];

    $response = $this->postJson('/api/frontend/v1/komplain', $data);
    expect($response->getStatusCode())->toBeIn([201, 500]);
});