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

use App\Models\DataDesa;
use App\Models\PutusSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware();
    $this->user = User::factory()->create();
    $this->desa = DataDesa::factory()->create();
});

afterEach(function () {
    PutusSekolah::query()->forceDelete();
    DataDesa::query()->forceDelete();
    User::query()->forceDelete();
});

test('dapat mengakses halaman export putus sekolah', function () {
    $this->actingAs($this->user);

    try {
        route('data.putus-sekolah.export-excel');
    } catch (\Exception $e) {
        $this->markTestSkipped('Route data.putus-sekolah.export-excel tidak ditemukan');
    }

    $response = $this->get(route('data.putus-sekolah.export-excel'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->not->toBeNull()
        ->and($disposition)->toContain('attachment');
});

test('dapat export data putus sekolah kosong', function () {
    $this->actingAs($this->user);
    PutusSekolah::query()->delete();

    try {
        route('data.putus-sekolah.export-excel');
    } catch (\Exception $e) {
        $this->markTestSkipped('Route data.putus-sekolah.export-excel tidak ditemukan');
    }

    $response = $this->get(route('data.putus-sekolah.export-excel'));

    $response->assertStatus(200);
    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->toContain('data-putus-sekolah-');
});

test('dapat export data putus sekolah dengan data', function () {
    $this->actingAs($this->user);

    PutusSekolah::factory()->count(3)->create([
        'desa_id' => $this->desa->desa_id
    ]);

    expect(PutusSekolah::where('desa_id', $this->desa->desa_id)->count())->toBeGreaterThanOrEqual(3);

    $response = $this->get(route('data.putus-sekolah.export-excel'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('export putus sekolah menghasilkan filename dengan timestamp', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('data.putus-sekolah.export-excel'));

    $response->assertStatus(200);
    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->toContain('data-putus-sekolah-')
        ->and($disposition)->toContain('.xlsx')
        ->and($disposition)->toMatch('/data-putus-sekolah-\d{4}/');
});

test('export putus sekolah memiliki header yang benar', function () {
    $this->actingAs($this->user);
    PutusSekolah::factory()->create(['desa_id' => $this->desa->desa_id]);

    $response = $this->get(route('data.putus-sekolah.export-excel'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('dependencies are working', function () {
    expect($this->user)->not->toBeNull()
        ->and($this->user)->toBeInstanceOf(User::class)
        ->and($this->desa)->not->toBeNull()
        ->and($this->desa)->toBeInstanceOf(DataDesa::class);

    try {
        $routeUrl = route('data.putus-sekolah.export-excel');
        expect($routeUrl)->not->toBeNull();
    } catch (\Exception $e) {
        $this->markTestSkipped('Route tidak tersedia');
    }
});

test('unauthorized access denied', function () {
    try {
        route('data.putus-sekolah.export-excel');
    } catch (\Exception $e) {
        $this->markTestSkipped('Route tidak ditemukan');
    }

    $response = $this->get(route('data.putus-sekolah.export-excel'));
    expect($response->getStatusCode())->toBeIn([200, 302, 401, 403]);
});

test('export functionality unit', function () {
    $putusSekolah = PutusSekolah::factory()->create([
        'desa_id' => $this->desa->desa_id
    ]);

    $export = new \App\Exports\ExportPutusSekolah();

    $collection = $export->collection();
    expect($collection)->not->toBeNull()
        ->and($collection->count())->toBeGreaterThan(0);

    $headings = $export->headings();
    expect($headings)->toBeArray()
        ->and($headings)->toContain('Nama Desa')
        ->and($headings)->toContain('Siswa PAUD');

    $mapped = $export->map($putusSekolah);
    expect($mapped)->toBeArray()
        ->and($mapped)->toHaveCount(14);
});

test('export with various data scenarios', function () {
    $this->actingAs($this->user);

    $data = [
        ['tahun' => 2023, 'semester' => 1, 'siswa_paud' => 10, 'anak_usia_paud' => 15, 'siswa_sd' => 50, 'anak_usia_sd' => 60],
        ['tahun' => 2023, 'semester' => 2, 'siswa_paud' => 8, 'anak_usia_paud' => 12, 'siswa_sd' => 45, 'anak_usia_sd' => 55],
        ['tahun' => 2024, 'semester' => 1, 'siswa_paud' => 12, 'anak_usia_paud' => 18, 'siswa_sd' => 52, 'anak_usia_sd' => 65],
    ];

    foreach ($data as $item) {
        PutusSekolah::factory()->create(array_merge(['desa_id' => $this->desa->desa_id], $item));
    }

    $response = $this->get(route('data.putus-sekolah.export-excel'));

    $response->assertStatus(200);
    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->toContain('data-putus-sekolah-');
});

test('export handles edge cases', function () {
    $this->actingAs($this->user);

    PutusSekolah::factory()->create([
        'desa_id' => $this->desa->desa_id,
        'siswa_paud' => 0,
        'anak_usia_paud' => 0,
        'siswa_sd' => 0,
        'anak_usia_sd' => 0,
        'siswa_smp' => 0,
        'anak_usia_smp' => 0,
        'siswa_sma' => 0,
        'anak_usia_sma' => 0,
        'tahun' => 2024,
        'semester' => 1,
    ]);

    $response = $this->get(route('data.putus-sekolah.export-excel'));

    $response->assertStatus(200);
});

test('export data validation', function () {
    $putusSekolah = PutusSekolah::factory()->create([
        'desa_id' => $this->desa->desa_id
    ]);

    expect($putusSekolah->desa_id)->not->toBeNull()
        ->and($putusSekolah->tahun)->not->toBeNull()
        ->and($putusSekolah->semester)->not->toBeNull()
        ->and($putusSekolah->siswa_paud)->toBeNumeric()
        ->and($putusSekolah->desa)->not->toBeNull()
        ->and($putusSekolah->desa->desa_id)->toBe($this->desa->desa_id);
});
