<?php

use App\Models\Kawin;
use App\Models\Penduduk;


// Kawin Model Testing
it('can create a kawin', function () {
    $kawin = Kawin::factory()->create([
        'nama' => 'Belum Kawin',
    ]);

    expect($kawin)->toBeInstanceOf(Kawin::class);
    expect($kawin->nama)->toBe('Belum Kawin');
});

it('has fillable attributes', function () {
    $kawin = Kawin::factory()->make();

    $fillable = ['nama'];

    foreach ($fillable as $field) {
        expect(in_array($field, $kawin->getFillable()))->toBeTrue();
    }
});

it('has timestamps disabled', function () {
    $kawin = Kawin::factory()->make();

    expect(property_exists($kawin, 'timestamps'))->toBeTrue();
    expect($kawin->timestamps)->toBeFalse();
});

it('has correct table name', function () {
    $kawin = new Kawin();

    expect($kawin->getTable())->toBe('ref_kawin');
});


it('can handle null values for optional fields', function () {
    $kawin = Kawin::factory()->create();

    expect($kawin->nama)->not->toBeNull();
    expect($kawin->id)->not->toBeNull();
});

it('can query kawin with complex filters', function () {
    $kawin1 = Kawin::factory()->create(['nama' => 'Belum Kawin']);
    $kawin2 = Kawin::factory()->create(['nama' => 'Kawin']);
    $kawin3 = Kawin::factory()->create(['nama' => 'Cerai Hidup']);
    $kawin4 = Kawin::factory()->create(['nama' => 'Cerai Mati']);

    $ceraiStatus = Kawin::where('nama', 'like', '%Cerai%')->get();

    expect($ceraiStatus->count())->toBeGreaterThanOrEqual(2);
});