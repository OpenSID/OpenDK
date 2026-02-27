<?php

use App\Services\KeluargaService;

use Illuminate\Support\Facades\Http;

// KeluargaService Testing
it('can instantiate keluarga service', function () {
    $service = new KeluargaService();
    
    expect($service)->toBeInstanceOf(KeluargaService::class);
});

it('can get keluarga by ID from API', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'no_kk' => '1234567890123456',
                    'nik_kepala' => '1234567890123457',
                    'nama_kk' => 'John Doe',
                    'tgl_daftar' => '2020-01-01',
                    'tgl_cetak_kk' => '2020-01-15',
                    'desa' => 'Desa Test',
                    'alamat_plus_dusun' => 'Alamat Test',
                    'rt' => '01',
                    'rw' => '02',
                    'anggota' => 4
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $keluarga = $service->keluarga(1);
    
    expect($keluarga)->toBeObject();
    expect($keluarga->id)->toBe(1);
    expect($keluarga->no_kk)->toBe('1234567890123456');
    expect($keluarga->nik_kepala)->toBe('1234567890123457');
    expect($keluarga->nama_kk)->toBe('John Doe');
    expect($keluarga->tgl_daftar)->toBe('2020-01-01');
    expect($keluarga->tgl_cetak_kk)->toBe('2020-01-15');
    expect($keluarga->desa)->toBe('Desa Test');
    expect($keluarga->alamat)->toBe('Alamat Test');
    expect($keluarga->rt)->toBe('01');
    expect($keluarga->rw)->toBe('02');
    expect($keluarga->anggota)->toBe(4);
});

it('handles missing attributes in keluarga data', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'no_kk' => '1234567890123456'
                    // Missing other attributes
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $keluarga = $service->keluarga(1);
    
    expect($keluarga)->toBeObject();
    expect($keluarga->id)->toBe(1);
    expect($keluarga->no_kk)->toBe('1234567890123456');
    expect($keluarga->nik_kepala)->toBeNull();
    expect($keluarga->nama_kk)->toBeNull();
    expect($keluarga->tgl_daftar)->toBeNull();
    expect($keluarga->tgl_cetak_kk)->toBeNull();
    expect($keluarga->desa)->toBeNull();
    expect($keluarga->alamat)->toBeNull();
    expect($keluarga->rt)->toBeNull();
    expect($keluarga->rw)->toBeNull();
    expect($keluarga->anggota)->toBeNull();
});

it('can get jumlah keluarga from API', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            'meta' => [
                'pagination' => [
                    'total' => 50
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $jumlah = $service->jumlahKeluarga();
    
    expect($jumlah)->toBe(50);
});

it('returns 0 when API response has no total', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            'meta' => [
                'pagination' => []
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $jumlah = $service->jumlahKeluarga();
    
    expect($jumlah)->toBe(0);
});

it('can export keluarga data from API', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'nik_kepala' => '1234567890123457',
                    'nama_kk' => 'John Doe',
                    'no_kk' => '1234567890123456',
                    'alamat' => 'Alamat Test',
                    'dusun' => 'Dusun Test',
                    'rw' => '02',
                    'rt' => '01',
                    'desa' => 'Desa Test',
                    'tgl_daftar' => '2020-01-01',
                    'tgl_cetak_kk' => '2020-01-15'
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $exportData = $service->exportKeluarga();
    
    expect($exportData)->toHaveCount(1);
    expect($exportData->first()->id)->toBe(1);
    expect($exportData->first()->nik_kepala)->toBe('1234567890123457');
    expect($exportData->first()->kepala_kk_nama)->toBe('John Doe');
    expect($exportData->first()->no_kk)->toBe('1234567890123456');
    expect($exportData->first()->alamat)->toBe('Alamat Test');
    expect($exportData->first()->dusun)->toBe('Dusun Test');
    expect($exportData->first()->rw)->toBe('02');
    expect($exportData->first()->rt)->toBe('01');
    expect($exportData->first()->desa->nama)->toBe('Desa Test');
    expect($exportData->first()->tgl_daftar)->toBe('2020-01-01');
    expect($exportData->first()->tgl_cetak_kk)->toBe('2020-01-15');
});

it('handles missing attributes in export data', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'no_kk' => '1234567890123456'
                    // Missing other attributes
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $exportData = $service->exportKeluarga();
    
    expect($exportData)->toHaveCount(1);
    expect($exportData->first()->id)->toBe(1);
    expect($exportData->first()->nik_kepala)->toBe('');
    expect($exportData->first()->kepala_kk_nama)->toBe('');
    expect($exportData->first()->no_kk)->toBe('1234567890123456');
    expect($exportData->first()->alamat)->toBe('');
    expect($exportData->first()->dusun)->toBe('');
    expect($exportData->first()->rw)->toBe('');
    expect($exportData->first()->rt)->toBe('');
    expect($exportData->first()->desa_nama)->toBe('');
    expect($exportData->first()->tgl_daftar)->toBeNull();
    expect($exportData->first()->tgl_cetak_kk)->toBeNull();
});

it('handles dash in tgl_cetak_kk field', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'no_kk' => '1234567890123456',
                    'tgl_cetak_kk' => '-'
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $exportData = $service->exportKeluarga();
    
    expect($exportData)->toHaveCount(1);
    expect($exportData->first()->tgl_cetak_kk)->toBeNull();
});

it('can apply filters to jumlah keluarga', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            'meta' => [
                'pagination' => [
                    'total' => 25
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $jumlah = $service->jumlahKeluarga(['filter[dusun]' => 'Dusun 1']);
    
    expect($jumlah)->toBe(25);
});

it('can export keluarga with custom parameters', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'no_kk' => '1234567890123456'
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $exportData = $service->exportKeluarga(['filter[rt]' => '01'], true);
    
    expect($exportData)->toHaveCount(1);
});

it('can export all keluarga data', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'no_kk' => '1234567890123456'
                ]
            ],
            [
                'id' => 2,
                'attributes' => [
                    'no_kk' => '1234567890123457'
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $exportData = $service->exportKeluarga([], true);
    
    expect($exportData)->toHaveCount(2);
});

it('handles empty API response for keluarga', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([], 200)
    ]);

    $service = new KeluargaService();
    
    // This should handle empty response gracefully
    expect(fn() => $service->keluarga(1))->toThrow(\Exception::class);
});

it('handles API errors gracefully', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([], 500)
    ]);

    $service = new KeluargaService();
    
    // This should handle API errors gracefully
    expect(fn() => $service->keluarga(1))->toThrow(\Exception::class);
});

it('can export keluarga with null timestamps', function () {
    Http::fake([
        '*/api/v1/keluarga*' => Http::response([
            [
                'id' => 1,
                'attributes' => [
                    'no_kk' => '1234567890123456'
                ]
            ]
        ], 200)
    ]);

    $service = new KeluargaService();
    $exportData = $service->exportKeluarga();
    
    expect($exportData)->toHaveCount(1);
    expect($exportData->first()->created_at)->toBeNull();
    expect($exportData->first()->updated_at)->toBeNull();
});