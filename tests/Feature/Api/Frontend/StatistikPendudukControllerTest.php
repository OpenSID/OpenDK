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
use App\Models\SettingAplikasi;
use App\Repositories\StatistikPendudukApiRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
    $this->repository = Mockery::mock(StatistikPendudukApiRepository::class);
    $this->app->instance(StatistikPendudukApiRepository::class, $this->repository);
    Cache::flush();
});

test('index returns statistik penduduk data', function () {
    $expectedData = [
        [
            'id' => 1,
            'yearList' => [2020, 2021, 2022, 2023, 2024],
            'dashboard' => [
                'total_penduduk' => 1000,
                'penduduk_laki' => 500,
                'penduduk_perempuan' => 500,
            ],
            'chart' => [
                'penduduk' => ['labels' => ['2020', '2021', '2022', '2023', '2024'], 'data' => [800, 850, 900, 950, 1000]],
                'penduduk-usia' => ['labels' => ['0-5', '6-12', '13-18', '19-40', '41-60', '60+'], 'data' => [100, 150, 120, 300, 200, 130]],
                'penduduk-pendidikan' => ['labels' => ['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'D1/D2/D3', 'S1', 'S2/S3'], 'data' => [50, 200, 250, 300, 100, 80, 20]],
                'penduduk-golongan-darah' => ['labels' => ['A', 'B', 'AB', 'O'], 'data' => [250, 250, 200, 300]],
                'penduduk-kawin' => ['labels' => ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'], 'data' => [300, 600, 50, 50]],
                'penduduk-agama' => ['labels' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'], 'data' => [800, 50, 30, 50, 50, 20]],
            ]
        ]
    ];

    $this->repository->shouldReceive('data')->once()->with('Semua', date('Y'))->andReturn($expectedData);

    $response = $this->getJson('/api/frontend/v1/statistik-penduduk');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'type',
                'id',
                'attributes' => [
                    'yearList',
                    'dashboard',
                    'chart' => [
                        'penduduk',
                        'penduduk-usia',
                        'penduduk-pendidikan',
                        'penduduk-golongan-darah',
                        'penduduk-kawin',
                        'penduduk-agama',
                    ]
                ]
            ]
        ]
    ]);
});

test('index with desa parameter', function () {
    $this->repository->shouldReceive('data')->once()->with('Pendidikan', date('Y'))->andReturn([]);
    $response = $this->getJson('/api/frontend/v1/statistik-penduduk?desa=Pendidikan');
    $response->assertStatus(200);
});

test('index with tahun parameter', function () {
    $this->repository->shouldReceive('data')->once()->with('Semua', '2023')->andReturn([]);
    $response = $this->getJson('/api/frontend/v1/statistik-penduduk?tahun=2023');
    $response->assertStatus(200);
});

test('index with desa and tahun parameters', function () {
    $this->repository->shouldReceive('data')->once()->with('Kesehatan', '2022')->andReturn([]);
    $response = $this->getJson('/api/frontend/v1/statistik-penduduk?desa=Kesehatan&tahun=2022');
    $response->assertStatus(200);
});

test('index with pagination parameters', function () {
    $this->repository->shouldReceive('data')->once()->with('Semua', date('Y'))->andReturn([]);
    $response = $this->getJson('/api/frontend/v1/statistik-penduduk?page[number]=2&page[size]=10');
    $response->assertStatus(200);
});

test('index with search parameter', function () {
    $this->repository->shouldReceive('data')->once()->with('Semua', date('Y'))->andReturn([]);
    $response = $this->getJson('/api/frontend/v1/statistik-penduduk?search=test');
    $response->assertStatus(200);
});

test('index with sort and order parameters', function () {
    $this->repository->shouldReceive('data')->once()->with('Semua', date('Y'))->andReturn([]);
    $response = $this->getJson('/api/frontend/v1/statistik-penduduk?sort=nama&order=asc');
    $response->assertStatus(200);
});

test('index with include parameter', function () {
    $this->repository->shouldReceive('data')->once()->with('Semua', date('Y'))->andReturn([]);
    $response = $this->getJson('/api/frontend/v1/statistik-penduduk?include=penduduk');
    $response->assertStatus(200);
});

test('index with all parameters', function () {
    $this->repository->shouldReceive('data')->once()->with('Ekonomi', '2023')->andReturn([]);
    $response = $this->getJson('/api/frontend/v1/statistik-penduduk?desa=Ekonomi&tahun=2023&page[number]=1&page[size]=15&search=test&sort=nama&order=desc&include=penduduk');
    $response->assertStatus(200);
});

test('index with empty data', function () {
    $this->repository->shouldReceive('data')->once()->with('Semua', date('Y'))->andReturn([]);
    $response = $this->getJson('/api/frontend/v1/statistik-penduduk');
    $response->assertStatus(200);
    $response->assertJson(['data' => []]);
});
