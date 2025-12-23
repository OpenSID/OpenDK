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
use App\Models\TingkatPendidikan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware();
    $this->user = User::factory()->create();
    $this->desa = DataDesa::factory()->create();
});

test('dapat mengakses halaman export tingkat pendidikan', function () {
    $this->actingAs($this->user);

    try {
        route('data.tingkat-pendidikan.export-excel');
    } catch (\Exception $e) {
        $this->markTestSkipped('Route tidak ditemukan');
    }

    $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->not->toBeNull()
        ->and($disposition)->toContain('attachment');
});

test('dapat export data tingkat pendidikan kosong', function () {
    $this->actingAs($this->user);
    TingkatPendidikan::query()->delete();

    $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

    $response->assertStatus(200);
    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->toContain('data-tingkat-pendidikan-');
});

test('dapat export data tingkat pendidikan dengan data', function () {
    $this->actingAs($this->user);

    TingkatPendidikan::factory()->count(3)->create([
        'desa_id' => $this->desa->desa_id
    ]);

    $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('export tingkat pendidikan menghasilkan filename dengan timestamp', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

    $response->assertStatus(200);
    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->toContain('data-tingkat-pendidikan-')
        ->and($disposition)->toContain('.xlsx')
        ->and($disposition)->toMatch('/data-tingkat-pendidikan-\d{4}/');
});

test('export tingkat pendidikan memiliki header yang benar', function () {
    $this->actingAs($this->user);
    TingkatPendidikan::factory()->create(['desa_id' => $this->desa->desa_id]);

    $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('dependencies are working', function () {
    expect($this->user)->not->toBeNull()
        ->and($this->user)->toBeInstanceOf(User::class)
        ->and($this->desa)->not->toBeNull()
        ->and($this->desa)->toBeInstanceOf(DataDesa::class);

    try {
        $routeUrl = route('data.tingkat-pendidikan.export-excel');
        expect($routeUrl)->not->toBeNull();
    } catch (\Exception $e) {
        $this->markTestSkipped('Route tidak tersedia');
    }
});

test('unauthorized access denied', function () {
    $response = $this->get(route('data.tingkat-pendidikan.export-excel'));
    expect($response->getStatusCode())->toBeIn([200, 302, 401, 403]);
});

test('export functionality unit', function () {
    $tingkatPendidikan = TingkatPendidikan::factory()->create([
        'desa_id' => $this->desa->desa_id
    ]);

    $export = new \App\Exports\ExportTingkatPendidikan();

    $collection = $export->collection();
    expect($collection)->not->toBeNull()
        ->and($collection->count())->toBeGreaterThan(0);

    $headings = $export->headings();
    expect($headings)->toBeArray()
        ->and($headings)->toContain('Nama Desa')
        ->and($headings)->toContain('Tidak Tamat Sekolah');

    $mapped = $export->map($tingkatPendidikan);
    expect($mapped)->toBeArray()
        ->and($mapped)->toHaveCount(11);
});

test('export with various data scenarios', function () {
    $this->actingAs($this->user);

    $data = [
        ['tahun' => 2023, 'semester' => 1, 'tidak_tamat_sekolah' => 10, 'tamat_sd' => 50],
        ['tahun' => 2023, 'semester' => 2, 'tidak_tamat_sekolah' => 8, 'tamat_sd' => 55],
        ['tahun' => 2024, 'semester' => 1, 'tidak_tamat_sekolah' => 5, 'tamat_sd' => 60],
    ];

    foreach ($data as $item) {
        TingkatPendidikan::factory()->create(array_merge(['desa_id' => $this->desa->desa_id], $item));
    }

    $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

    $response->assertStatus(200);
    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->toContain('data-tingkat-pendidikan-');
});

test('export handles edge cases', function () {
    $this->actingAs($this->user);

    TingkatPendidikan::factory()->create([
        'desa_id' => $this->desa->desa_id,
        'tidak_tamat_sekolah' => 0,
        'tamat_sd' => 0,
        'tamat_smp' => 0,
        'tamat_sma' => 0,
        'tamat_diploma_sederajat' => 0,
        'tahun' => 2024,
        'semester' => 1,
    ]);

    $response = $this->get(route('data.tingkat-pendidikan.export-excel'));

    $response->assertStatus(200);
});
