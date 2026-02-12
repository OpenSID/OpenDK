<?php

use App\Services\PendudukService;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\DataDesa;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// PendudukService Testing
it('can instantiate penduduk service', function () {
    $service = new PendudukService();
    
    expect($service)->toBeInstanceOf(PendudukService::class);
});

it('can get jumlah penduduk from API', function () {
    Http::fake([
        '*/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            'meta' => [
                'pagination' => [
                    'total' => 100
                ]
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $jumlah = $service->jumlahPenduduk();
    
    expect($jumlah)->toBe(100);
});

it('returns 0 when API response has no total', function () {
    Http::fake([
        '*/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            'meta' => [
                'pagination' => []
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $jumlah = $service->jumlahPenduduk();
    
    expect($jumlah)->toBe(0);
});

it('can get desa list from API', function () {
    Http::fake([
        '*/api/v1/desa*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'kode_desa' => '3201010001',
                    'nama_desa' => 'Desa Test'
                ]
            ],
            [
                'id' => 2,
                'attributes' => [
                    'kode_desa' => '3201010002',
                    'nama_desa' => 'Desa Test 2'
                ]
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $desaList = $service->desa();
    
    expect($desaList)->toHaveCount(2);
    expect($desaList->first()->id)->toBe(1);
    expect($desaList->first()->kode_desa)->toBe('3201010001');
    expect($desaList->first()->nama_desa)->toBe('Desa Test');
});

it('can export penduduk data from API', function () {
    Http::fake([
        '*/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'nama' => 'John Doe',
                    'nik' => '1234567890123456',
                    'keluarga' => [
                        'no_kk' => '1234567890123457'
                    ],
                    'config' => [
                        'nama_desa' => 'Desa Test'
                    ],
                    'alamat_sekarang' => 'Alamat Test',
                    'pendidikan_k_k' => [
                        'nama' => 'SMA'
                    ],
                    'tanggallahir' => '1990-01-01',
                    'umur' => 30,
                    'pekerjaan' => [
                        'nama' => 'Pegawai'
                    ],
                    'status_kawin' => [
                        'nama' => 'Belum Kawin'
                    ]
                ]
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $exportData = $service->exportPenduduk(10, 1, 'test');
    
    expect($exportData)->toHaveCount(1);
    expect($exportData->first()['ID'])->toBe(1);
    expect($exportData->first()['nama'])->toBe('John Doe');
    expect($exportData->first()['nik'])->toBe('1234567890123456');
    expect($exportData->first()['no_kk'])->toBe('1234567890123457');
    expect($exportData->first()['nama_desa'])->toBe('Desa Test');
    expect($exportData->first()['alamat'])->toBe('Alamat Test');
    expect($exportData->first()['pendidikan'])->toBe('SMA');
    expect($exportData->first()['tanggal_lahir'])->toBe('1990-01-01');
    expect($exportData->first()['umur'])->toBe(30);
    expect($exportData->first()['pekerjaan'])->toBe('Pegawai');
    expect($exportData->first()['status_kawin'])->toBe('Belum Kawin');
});

it('can check penduduk by NIK and birth date', function () {
    Http::fake([
        '*/api/v1/opendk/penduduk-nik-tanggalahir' => Http::response([
            'data' => [                
                'nama' => 'John Doe',
                'nik' => '1234567890123456'
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $penduduk = $service->cekPendudukNikTanggalLahir('1234567890123456', '1990-01-01');
    
    expect($penduduk)->toBeInstanceOf(Penduduk::class);    
    expect($penduduk->nama)->toBe('John Doe');
    expect($penduduk->nik)->toBe('1234567890123456');
});

it('returns null when penduduk not found by NIK', function () {
    Http::fake([
        '*/api/v1/opendk/penduduk-nik-tanggalahir' => Http::response([
            'data' => null
        ], 200)
    ]);

    $service = new PendudukService();
    $penduduk = $service->cekPendudukNikTanggalLahir('9999999999999999', '1990-01-01');
    
    expect($penduduk)->toBeNull();
});

it('returns null when API request fails', function () {
    Http::fake([
        '*/api/v1/opendk/penduduk-nik-tanggalahir' => Http::response(['error' => 'Server Error'], 500)
    ]);

    $service = new PendudukService();
    $penduduk = $service->cekPendudukNikTanggalLahir('1234567890123456', '1990-01-01');
    
    expect($penduduk)->toBeNull();
});

it('handles exceptions gracefully', function () {
    Http::fake([
        '*/api/v1/opendk/penduduk-nik-tanggalahir' => Http::response(['error' => 'Server Error'], 500)
    ]);

    $service = new PendudukService();
    $penduduk = $service->cekPendudukNikTanggalLahir('1234567890123456', '1990-01-01');
    
    expect($penduduk)->toBeNull();
});

it('can apply filters to jumlah penduduk', function () {
    Http::fake([
        '*/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            'meta' => [
                'pagination' => [
                    'total' => 50
                ]
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $jumlah = $service->jumlahPenduduk(['filter[sex]' => '1']);
    
    expect($jumlah)->toBe(50);
});

it('can apply filters to desa list', function () {
    Http::fake([
        '*/api/v1/desa*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'kode_desa' => '3201010001',
                    'nama_desa' => 'Desa Filtered'
                ]
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $desaList = $service->desa(['filter[nama]' => 'Filtered']);
    
    expect($desaList)->toHaveCount(1);
    expect($desaList->first()->nama_desa)->toBe('Desa Filtered');
});

it('can export penduduk with pagination', function () {
    Http::fake([
        '*/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'nama' => 'John Doe',
                    'nik' => '1234567890123456'
                ]
            ],
            [
                'id' => 2,
                'attributes' => [
                    'nama' => 'Jane Doe',
                    'nik' => '1234567890123457'
                ]
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $exportData = $service->exportPenduduk(2, 1, 'test');
    
    expect($exportData)->toHaveCount(2);
    expect($exportData->first()['nama'])->toBe('John Doe');
    expect($exportData->last()['nama'])->toBe('Jane Doe');
});

it('can export penduduk with search term', function () {
    Http::fake([
        '*/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'nama' => 'John Search',
                    'nik' => '1234567890123456'
                ]
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $exportData = $service->exportPenduduk(10, 1, 'Search');
    
    expect($exportData)->toHaveCount(1);
    expect($exportData->first()['nama'])->toBe('John Search');
});

it('handles missing attributes in export data', function () {
    Http::fake([
        '*/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'nama' => 'John Doe'
                    // Missing other attributes
                ]
            ]
        ], 200)
    ]);

    $service = new PendudukService();
    $exportData = $service->exportPenduduk(10, 1, 'test');
    
    expect($exportData)->toHaveCount(1);
    expect($exportData->first()['nama'])->toBe('John Doe');
    expect($exportData->first()['nik'])->toBe('');
    expect($exportData->first()['no_kk'])->toBe('');
    expect($exportData->first()['nama_desa'])->toBe('');
    expect($exportData->first()['alamat'])->toBe('');
    expect($exportData->first()['pendidikan'])->toBe('');
    expect($exportData->first()['tanggal_lahir'])->toBe('');
    expect($exportData->first()['umur'])->toBe('');
    expect($exportData->first()['pekerjaan'])->toBe('');
    expect($exportData->first()['status_kawin'])->toBe('');
});