<?php

use App\Models\DataDesa;


it('can create a data desa', function () {
    $dataDesa = DataDesa::factory()->create([
        'nama' => 'Desa Makmur',
        'desa_id' => '001',
        'website' => 'https://example.com',
    ]);

    expect($dataDesa)->toBeInstanceOf(DataDesa::class);
    expect($dataDesa->nama)->toBe('Desa Makmur');
    expect($dataDesa->desa_id)->toBe('001');
    expect($dataDesa->website)->toBe('https://example.com');
});

it('has fillable attributes', function () {
    $dataDesa = DataDesa::factory()->make();

    $fillable = [
        'desa_id', 'nama', 'sebutan_desa', 'website', 'luas_wilayah', 'path'
    ];

    foreach ($fillable as $field) {
        expect(in_array($field, $dataDesa->getFillable()))->toBeTrue();
    }
});

it('has timestamps enabled', function () {
    $dataDesa = new DataDesa();

    // Check that timestamps are enabled by default
    expect(property_exists($dataDesa, 'timestamps'))->toBeTrue();
    expect($dataDesa->timestamps)->toBeTrue();
});

it('has keluarga relationship', function () {
    $dataDesa = DataDesa::factory()->create();

    expect($dataDesa->keluarga())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('has penduduk relationship', function () {
    $dataDesa = DataDesa::factory()->create();

    expect($dataDesa->penduduk())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('can be filtered by nama', function () {
    DataDesa::factory()->create(['nama' => 'Desa A']);
    DataDesa::factory()->create(['nama' => 'Desa B']);

    $desaA = DataDesa::where('nama', 'Desa A')->get();
    $desaB = DataDesa::where('nama', 'Desa B')->get();

    expect($desaA)->toHaveCount(1);
    expect($desaB)->toHaveCount(1);
});

it('has correct table name', function () {
    $dataDesa = new DataDesa();

    expect($dataDesa->getTable())->toBe('das_data_desa');
});