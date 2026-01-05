<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\Potensi;
use App\Models\SettingAplikasi;
use App\Models\TipePotensi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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

    // Create test data
    $tipePotensi = TipePotensi::create([
        'nama_kategori' => 'Test Kategori',
        'slug' => 'test-kategori',
    ]);

    Potensi::create([
        'kategori_id' => $tipePotensi->id,
        'nama_potensi' => 'Potensi 1',
        'deskripsi' => 'Deskripsi potensi 1',
        'lokasi' => 'Lokasi potensi 1',
        'file_gambar' => 'storage/potensi_kecamatan//cDEnWmVEkFlBvIIEDiJxRba4wH2tsRaurHLvIydW.png',
        'long' => null,
        'lat' => null,
    ]);

    Potensi::create([
        'kategori_id' => $tipePotensi->id,
        'nama_potensi' => 'Potensi 2',
        'deskripsi' => 'Deskripsi potensi 2',
        'lokasi' => 'Lokasi potensi 2',
        'file_gambar' => '/img/no-image.png',
        'long' => null,
        'lat' => null,
    ]);
});

test('potensi api returns correct structure', function () {
    $response = $this->getJson('/api/frontend/v1/potensi');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'type',
                'id',
                'attributes' => [
                    'kategori_id',
                    'nama_potensi',
                    'deskripsi',
                    'lokasi',
                    'file_gambar',
                    'long',
                    'lat',
                    'created_at',
                    'updated_at',
                    'file_gambar_path',
                ]
            ]
        ],
        'meta' => ['pagination' => ['total', 'count', 'per_page', 'current_page', 'total_pages']],
        'links'
    ]);

    $data = $response->json('data');
    expect($data[0]['type'])->toBe('potensi')
        ->and($data[0]['id'])->toBeString()
        ->and($data[0]['attributes']['nama_potensi'])->toBe('Potensi 1')
        ->and($data[0]['attributes']['file_gambar_path'])->toBeString()->not->toBeEmpty();
});

test('potensi api pagination', function () {
    $response = $this->getJson('/api/frontend/v1/potensi?page[number]=1&page[size]=1');

    $response->assertStatus(200);

    $pagination = $response->json('meta.pagination');
    expect($pagination['total'])->toBe(2)
        ->and($pagination['count'])->toBe(1)
        ->and($pagination['per_page'])->toBe(1)
        ->and($pagination['current_page'])->toBe(1)
        ->and($pagination['total_pages'])->toBe(2);
});