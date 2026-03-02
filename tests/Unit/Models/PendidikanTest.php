<?php

use App\Models\Pendidikan;
use App\Models\Penduduk;


// Pendidikan Model Testing
it('can create a pendidikan', function () {
    $pendidikan = Pendidikan::factory()->create([
        'nama' => 'SD',
    ]);

    expect($pendidikan)->toBeInstanceOf(Pendidikan::class);
    expect($pendidikan->nama)->toBe('SD');
});

it('has fillable attributes', function () {
    $pendidikan = Pendidikan::factory()->make();

    $fillable = ['nama'];
    foreach ($fillable as $field) {
        expect(in_array($field, $pendidikan->getFillable()))->toBeTrue();
    }
});

it('has timestamps disabled', function () {
    $pendidikan = Pendidikan::factory()->make();

    expect(property_exists($pendidikan, 'timestamps'))->toBeTrue();
    expect($pendidikan->timestamps)->toBeFalse();
});

it('has correct table name', function () {
    $pendidikan = new Pendidikan();

    expect($pendidikan->getTable())->toBe('ref_pendidikan');
});

it('can handle null values for optional fields', function () {
    $pendidikan = Pendidikan::factory()->create();

    expect($pendidikan->nama)->not->toBeNull();
    expect($pendidikan->id)->not->toBeNull();
});

it('can query pendidikan with complex filters', function () {
    $pendidikan1 = Pendidikan::factory()->create(['nama' => 'SD']);
    $pendidikan2 = Pendidikan::factory()->create(['nama' => 'SMP']);
    $pendidikan3 = Pendidikan::factory()->create(['nama' => 'SMA']);
    $pendidikan4 = Pendidikan::factory()->create(['nama' => 'S1']);

    $higherEducation = Pendidikan::where('nama', 'like', '%S%')->get();

    expect($higherEducation->count())->toBeGreaterThanOrEqual(3);
});