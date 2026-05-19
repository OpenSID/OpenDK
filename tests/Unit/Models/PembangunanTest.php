<?php

use App\Models\Pembangunan;

beforeEach(function () {
    Pembangunan::query()->delete();
});

// ============================================================
// Tests for getSumberDanaFormattedAttribute() accessor
// ============================================================

it('returns plain text when sumber_dana is a regular string', function () {
    $pembangunan = Pembangunan::factory()->make([
        'sumber_dana' => 'Dana Desa',
        'desa_id' => '3301010000001',
    ]);

    expect($pembangunan->sumber_dana_formatted)->toBe('Dana Desa');
});

it('returns comma-separated text when sumber_dana is a JSON array', function () {
    $pembangunan = Pembangunan::factory()->make([
        'sumber_dana' => '["Dana Desa","ADD","APBD"]',
        'desa_id' => '3301010000001',
    ]);

    expect($pembangunan->sumber_dana_formatted)->toBe('Dana Desa, ADD, APBD');
});

it('returns single value when JSON array has one item', function () {
    $pembangunan = Pembangunan::factory()->make([
        'sumber_dana' => '["Dana Desa"]',
        'desa_id' => '3301010000001',
    ]);

    expect($pembangunan->sumber_dana_formatted)->toBe('Dana Desa');
});

it('returns dash when sumber_dana is empty string', function () {
    $pembangunan = Pembangunan::factory()->make([
        'sumber_dana' => '',
        'desa_id' => '3301010000001',
    ]);

    expect($pembangunan->sumber_dana_formatted)->toBe('-');
});

it('returns dash when sumber_dana is null', function () {
    $pembangunan = Pembangunan::factory()->make([
        'sumber_dana' => null,
        'desa_id' => '3301010000001',
    ]);

    expect($pembangunan->sumber_dana_formatted)->toBe('-');
});

// ============================================================
// Tests for getSumberDanaListAttribute() accessor
// ============================================================

it('returns array from JSON array for sumber_dana_list', function () {
    $pembangunan = Pembangunan::factory()->make([
        'sumber_dana' => '["Dana Desa","ADD"]',
        'desa_id' => '3301010000001',
    ]);

    expect($pembangunan->sumber_dana_list)->toBe(['Dana Desa', 'ADD']);
});

it('returns single-item array for plain text sumber_dana_list', function () {
    $pembangunan = Pembangunan::factory()->make([
        'sumber_dana' => 'Dana Desa',
        'desa_id' => '3301010000001',
    ]);

    expect($pembangunan->sumber_dana_list)->toBe(['Dana Desa']);
});

it('returns empty array when sumber_dana is empty for list', function () {
    $pembangunan = Pembangunan::factory()->make([
        'sumber_dana' => '',
        'desa_id' => '3301010000001',
    ]);

    expect($pembangunan->sumber_dana_list)->toBe([]);
});

it('returns empty array when sumber_dana is null for list', function () {
    $pembangunan = Pembangunan::factory()->make([
        'sumber_dana' => null,
        'desa_id' => '3301010000001',
    ]);

    expect($pembangunan->sumber_dana_list)->toBe([]);
});

// ============================================================
// Tests for export mapping (uses create since it needs DB)
// ============================================================

it('uses sumber_dana_formatted in export', function () {
    $desa = \App\Models\DataDesa::factory()->create();
    $pembangunan = Pembangunan::factory()->create([
        'sumber_dana' => '["Dana Desa","ADD"]',
        'desa_id' => $desa->desa_id,
    ]);

    $export = new \App\Exports\ExportPembangunan();
    $mapped = $export->map($pembangunan);

    // Column index 2 (0-based) is Sumber Dana
    expect($mapped[2])->toBe('Dana Desa, ADD');
});

it('uses original value in export when sumber_dana is not JSON', function () {
    $desa = \App\Models\DataDesa::factory()->create();
    $pembangunan = Pembangunan::factory()->create([
        'sumber_dana' => 'APBD',
        'desa_id' => $desa->desa_id,
    ]);

    $export = new \App\Exports\ExportPembangunan();
    $mapped = $export->map($pembangunan);

    expect($mapped[2])->toBe('APBD');
});