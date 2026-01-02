<?php

use App\Models\DataDesa;
use App\Models\Pesan;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test('pesan can use kode desa instead of id', function () {
    // Create a DataDesa with specific desa_id (kode_desa)
    $dataDesa = DataDesa::factory()->create([
        'desa_id' => '1234567890',
        'nama' => 'Desa Test'
    ]);

    // Create a Pesan using kode_desa
    $pesan = Pesan::factory()->create([
        'das_data_desa_id' => '1234567890',
        'judul' => 'Test Pesan'
    ]);

    // Test relationship works with kode_desa
    expect($pesan->dataDesa)->not->toBeNull()
        ->and($pesan->dataDesa->nama)->toBe('Desa Test')
        ->and($pesan->dataDesa->desa_id)->toBe('1234567890');
});

test('das data desa id is varchar type', function () {
    $pesan = Pesan::factory()->create([
        'das_data_desa_id' => 'ABC123DEF456' // Test with alphanumeric
    ]);

    expect($pesan->das_data_desa_id)
        ->toBeString()
        ->toBe('ABC123DEF456');
});

test('pesan relationship with data desa works', function () {
    // Create multiple DataDesa
    $desa1 = DataDesa::factory()->create(['desa_id' => '1111111111', 'nama' => 'Desa Satu']);
    $desa2 = DataDesa::factory()->create(['desa_id' => '2222222222', 'nama' => 'Desa Dua']);

    // Create Pesan for each desa
    $pesan1 = Pesan::factory()->create(['das_data_desa_id' => '1111111111']);
    $pesan2 = Pesan::factory()->create(['das_data_desa_id' => '2222222222']);

    // Test relationships
    expect($pesan1->dataDesa->nama)->toBe('Desa Satu')
        ->and($pesan2->dataDesa->nama)->toBe('Desa Dua');
});

test('pesan can handle null desa id', function () {
    $pesan = Pesan::factory()->create(['das_data_desa_id' => null]);

    expect($pesan->dataDesa)->toBeNull();
});

test('pesan handles nonexistent desa id', function () {
    $pesan = Pesan::factory()->create(['das_data_desa_id' => 'NONEXISTENT']);

    expect($pesan->dataDesa)->toBeNull();
});