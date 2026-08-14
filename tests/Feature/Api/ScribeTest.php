<?php

use function Pest\Laravel\get;

test('scribe documentation endpoint is accessible', function () {
    $response = get('/api-docs');
    $response->assertStatus(200);
});

test('scribe openapi spec file contains expected data', function () {
    $path = storage_path('app/scribe/openapi.yaml');
    expect(file_exists($path))->toBeTrue("Scribe OpenAPI spec file not found at $path");

    $content = file_get_contents($path);

    // Check for openapi version
    expect($content)->toContain('openapi:');

    // Check for frontend endpoints
    expect($content)->toContain('/api/frontend/v1/artikel');
    expect($content)->toContain('/api/frontend/v1/desa');

    // Check for internal endpoints
    expect($content)->toContain('/api/v1/auth/login');
    expect($content)->toContain('/api/v1/penduduk');
    expect($content)->toContain('/api/v1/identitas-desa');
});
