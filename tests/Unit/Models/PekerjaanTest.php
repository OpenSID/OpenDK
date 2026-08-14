<?php

use App\Models\Pekerjaan;
use App\Models\Penduduk;


// Pekerjaan Model Testing
it('can create a pekerjaan', function () {
    $pekerjaan = Pekerjaan::factory()->create([
        'nama' => 'Pegawai Negeri Sipil',
    ]);

    expect($pekerjaan)->toBeInstanceOf(Pekerjaan::class);
    expect($pekerjaan->nama)->toBe('Pegawai Negeri Sipil');
});

it('has fillable attributes', function () {
    $pekerjaan = Pekerjaan::factory()->make();

    $fillable = ['nama'];
    foreach ($fillable as $field) {
        expect(in_array($field, $pekerjaan->getFillable()))->toBeTrue();
    }
});

it('has timestamps disabled', function () {
    $pekerjaan = Pekerjaan::factory()->make();

    expect(property_exists($pekerjaan, 'timestamps'))->toBeTrue();
    expect($pekerjaan->timestamps)->toBeFalse();
});

it('has correct table name', function () {
    $pekerjaan = new Pekerjaan();

    expect($pekerjaan->getTable())->toBe('ref_pekerjaan');
});


it('can handle null values for optional fields', function () {
    $pekerjaan = Pekerjaan::factory()->create();

    expect($pekerjaan->nama)->not->toBeNull();
    expect($pekerjaan->id)->not->toBeNull();
});

it('can query pekerjaan with complex filters', function () {
    $pekerjaan1 = Pekerjaan::factory()->create(['nama' => 'Pegawai Negeri Sipil']);
    $pekerjaan2 = Pekerjaan::factory()->create(['nama' => 'Wiraswasta']);
    $pekerjaan3 = Pekerjaan::factory()->create(['nama' => 'Pedagang']);

    $filteredPekerjaan = Pekerjaan::where('nama', 'like', '%a%')->get();

    expect($filteredPekerjaan->count())->toBeGreaterThanOrEqual(2); // Pegawai Negeri Sipil, Pedagang
});