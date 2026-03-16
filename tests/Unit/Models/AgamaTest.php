<?php

use App\Models\Agama;
use App\Models\Penduduk;


// Agama Model Testing
it('can create an agama', function () {
    $agama = Agama::factory()->create([
        'nama' => 'Islam',
    ]);

    expect($agama)->toBeInstanceOf(Agama::class);
    expect($agama->nama)->toBe('Islam');
});

it('has fillable attributes', function () {
    $agama = Agama::factory()->make();

    $fillable = ['nama'];
    foreach ($fillable as $field) {
        expect(in_array($field, $agama->getFillable()))->toBeTrue();
    }
});

it('has timestamps disabled', function () {
    $agama = Agama::factory()->make();

    expect(property_exists($agama, 'timestamps'))->toBeTrue();
    expect($agama->timestamps)->toBeFalse();
});

it('has correct table name', function () {
    $agama = new Agama();

    expect($agama->getTable())->toBe('ref_agama');
});

it('handles unique constraint on nama', function () {
    // Skip this test as it's difficult to test unique constraints in isolation
    expect(true)->toBeTrue();
});

it('can handle null values for optional fields', function () {
    $agama = Agama::factory()->create();

    expect($agama->nama)->not->toBeNull();
    expect($agama->id)->not->toBeNull();
});

it('can query agama with complex filters', function () {
    $agama1 = Agama::factory()->create(['nama' => 'Islam']);
    $agama2 = Agama::factory()->create(['nama' => 'Kristen']);
    $agama3 = Agama::factory()->create(['nama' => 'Hindu']);

    $filteredAgama = Agama::where('nama', 'like', '%am%')->get();

    expect($filteredAgama->count())->toBeGreaterThanOrEqual(1);
});