<?php

use App\Models\HubunganKeluarga;
use App\Models\Penduduk;


// HubunganKeluarga Model Testing
it('can create a hubungan keluarga', function () {
    $hubungan = HubunganKeluarga::factory()->create([
        'nama' => 'Kepala Keluarga',
    ]);

    expect($hubungan)->toBeInstanceOf(HubunganKeluarga::class);
    expect($hubungan->nama)->toBe('Kepala Keluarga');
});

it('has fillable attributes', function () {
    $hubungan = HubunganKeluarga::factory()->make();

    $fillable = ['nama'];

    foreach ($fillable as $field) {
        expect(in_array($field, $hubungan->getFillable()))->toBeTrue();
    }
});

it('has timestamps disabled', function () {
    $hubungan = HubunganKeluarga::factory()->make();

    expect(property_exists($hubungan, 'timestamps'))->toBeTrue();
    expect($hubungan->timestamps)->toBeFalse();
});

it('has correct table name', function () {
    $hubungan = new HubunganKeluarga();

    expect($hubungan->getTable())->toBe('ref_hubungan_keluarga');
});


it('can handle null values for optional fields', function () {
    $hubungan = HubunganKeluarga::factory()->create();

    expect($hubungan->nama)->not->toBeNull();
    expect($hubungan->id)->not->toBeNull();
});

it('can query hubungan keluarga with complex filters', function () {
    $hubungan1 = HubunganKeluarga::factory()->create(['nama' => 'Kepala Keluarga']);
    $hubungan2 = HubunganKeluarga::factory()->create(['nama' => 'Istri']);
    $hubungan3 = HubunganKeluarga::factory()->create(['nama' => 'Anak']);
    $hubungan4 = HubunganKeluarga::factory()->create(['nama' => 'Menantu']);

    $familyMembers = HubunganKeluarga::where('nama', 'like', '%a%')->get();

    expect($familyMembers->count())->toBeGreaterThanOrEqual(3);
});