<?php

use App\Http\Controllers\Data\PendudukController;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

uses(DatabaseTransactions::class);

beforeEach(function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_server_database_gabungan'],
        ['value' => 'http://localhost:8000']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_database_gabungan'],
        ['value' => 'test-api-key']
    );
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '1']
    );
});

test('detail aborts 404 when id is missing', function () {
    $request = new Request();

    try {
        (new PendudukController())->detail($request);
        $this->fail('Expected HttpException was not thrown');
    } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
        expect($e->getStatusCode())->toBe(404);
    }
});

test('detail aborts 404 when penduduk not found', function () {
    Http::fake([
        '*/api/v1/penduduk*' => Http::response(['data' => []], 200)
    ]);

    $request = new Request(['id' => 999]);

    try {
        (new PendudukController())->detail($request);
        $this->fail('Expected HttpException was not thrown');
    } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
        expect($e->getStatusCode())->toBe(404);
    }
});

test('detail returns view with penduduk data', function () {
    Http::fake([
        '*/api/v1/penduduk*' => Http::response([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'nama' => 'John Doe',
                    'nik' => '1234567890123456',
                    'penduduk_hubungan' => ['nama' => 'Kepala Keluarga'],
                    'jenis_kelamin' => ['nama' => 'LAKI-LAKI'],
                    'agama' => ['nama' => 'Islam'],
                    'penduduk_status' => ['nama' => 'Tetap'],
                    'tempatlahir' => 'Jakarta',
                    'tanggallahir' => '1990-01-01',
                    'wajibKTP' => 'Ya',
                    'status_rekam_ktp' => ['nama' => 'Sudah Rekam'],
                    'elKTP' => '1',
                    'pendidikan_k_k' => ['nama' => 'SMA'],
                    'pendidikan' => ['nama' => 'SMA'],
                    'pekerjaan' => ['nama' => 'Pegawai'],
                    'warga_negara' => ['nama' => 'WNI'],
                    'telepon' => '08123456789',
                    'alamat_sekarang' => 'Jl. Test No. 1',
                    'status_kawin' => ['nama' => 'Belum Kawin'],
                    'golongan_darah' => ['nama' => 'O'],
                    'cacat' => ['nama' => 'Tidak Ada'],
                    'sakit_menahun' => ['nama' => 'Tidak Ada'],
                    'kb' => ['nama' => 'Tidak Menggunakan'],
                ]
            ]
        ], 200)
    ]);

    $request = new Request(['id' => 1]);
    $response = (new PendudukController())->detail($request);

    expect($response)->toBeInstanceOf(\Illuminate\Contracts\View\View::class);
    expect($response->getName())->toBe('data.penduduk.gabungan.show');
    expect($response->getData()['penduduk']->id)->toBe(1);
    expect($response->getData()['penduduk']->nama)->toBe('John Doe');
    expect($response->getData()['penduduk']->nik)->toBe('1234567890123456');
});
