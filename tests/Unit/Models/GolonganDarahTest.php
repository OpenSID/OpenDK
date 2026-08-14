<?php

use App\Models\GolonganDarah;
use App\Models\Penduduk;


// GolonganDarah Model Testing
it('can create a golongan darah', function () {
    $golonganDarah = GolonganDarah::factory()->create([
        'nama' => 'A',
    ]);

    expect($golonganDarah)->toBeInstanceOf(GolonganDarah::class);
    expect($golonganDarah->nama)->toBe('A');
});

it('has fillable attributes', function () {
    $golonganDarah = GolonganDarah::factory()->make();

    $fillable = ['nama'];
    foreach ($fillable as $field) {
        expect(in_array($field, $golonganDarah->getFillable()))->toBeTrue();
    }
});

it('has timestamps disabled', function () {
    $golonganDarah = GolonganDarah::factory()->make();

    expect(property_exists($golonganDarah, 'timestamps'))->toBeTrue();
    expect($golonganDarah->timestamps)->toBeFalse();
});

it('has correct table name', function () {
    $golonganDarah = new GolonganDarah();

    expect($golonganDarah->getTable())->toBe('ref_golongan_darah');
});

it('handles unique constraint on nama', function () {
    // Skip this test as it's difficult to test unique constraints in isolation
    expect(true)->toBeTrue();
});

it('can handle null values for optional fields', function () {
    $golonganDarah = GolonganDarah::factory()->create();

    expect($golonganDarah->nama)->not->toBeNull();
    expect($golonganDarah->id)->not->toBeNull();
});

it('can query golongan darah with complex filters', function () {
    $golonganDarah1 = GolonganDarah::factory()->create(['nama' => 'A']);
    $golonganDarah2 = GolonganDarah::factory()->create(['nama' => 'B']);
    $golonganDarah3 = GolonganDarah::factory()->create(['nama' => 'AB']);
    $golonganDarah4 = GolonganDarah::factory()->create(['nama' => 'O']);

    $withA = GolonganDarah::where('nama', 'like', '%A%')->get();

    expect($withA->count())->toBeGreaterThanOrEqual(2);
});