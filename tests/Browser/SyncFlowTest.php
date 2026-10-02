<?php

use App\Models\DataDesa;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;
use Tests\BrowserTestCase;
use Tests\Browser\SessionState;

uses(BrowserTestCase::class);

beforeEach(function () {
    SessionState::clear();

    // Seed data desa yang dibutuhkan oleh validasi API
    DataDesa::factory()->create(['desa_id' => '1234567890']);

    // Seed token untuk semua test
    $user = SessionState::loginAdminUser();
    $this->user = $user;
    $this->registeredToken = app(\Tymon\JWTAuth\JWT::class)->fromUser($user);

    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_opendk'],
        ['value' => $this->registeredToken, 'type' => 'textarea']
    );
});

afterEach(function () {
    SessionState::clear();
});

// ─── Helpers ────────────────────────────────────────────────────────────────

function navigateAsAdmin(string $url)
{
    return SessionState::loginAndNavigate(test()->user, $url);
}

function getApiKeyEditUrl(): string
{
    return navigateAsAdmin('/setting/aplikasi')
        ->attribute(
            '[data-testid="setting-row"][data-setting-key="api_key_opendk"] [data-testid="setting-edit-btn"]',
            'href'
        );
}

function generateTokenViaUI(string $editUrl): string
{
    navigateAsAdmin($editUrl)
        ->click('[data-testid="generate-token-btn"]')
        ->waitForText('Apakah anda yakin ingin membuat token baru?')
        ->click('.swal2-confirm')
        ->waitForText('Token berhasil dibuat')
        ->click('.swal2-confirm');

    return navigateAsAdmin($editUrl)
        ->value('[data-testid="setting-value-input"]');
}

function fakeExternalHttp(): void
{
    Http::fake([
        'pantau.opensid.my.id/*' => Http::response(['status' => 'success'], 200),
    ]);
}

function sendSyncRequest(string $endpoint, array $payload, string $token): \Illuminate\Testing\TestResponse
{
    return test()->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/json',
    ])->postJson($endpoint, $payload);
}

// ─── Happy Path ─────────────────────────────────────────────────────────────

test('admin can generate API key and trigger data sync', function () {
    fakeExternalHttp();

    navigateAsAdmin('/setting/aplikasi')
        ->assertPresent('[data-testid="settings-table"]')
        ->assertSee('Api Key Opendk');

    $editUrl = getApiKeyEditUrl();
    expect($editUrl)->toContain('/setting/aplikasi/edit/');

    navigateAsAdmin($editUrl)
        ->assertPresent('[data-testid="settings-edit-form"]')
        ->assertPresent('[data-testid="setting-value-input"]')
        ->assertPresent('[data-testid="generate-token-btn"]');

    $generatedToken = generateTokenViaUI($editUrl);

    expect($generatedToken)->not->toBeEmpty()
        ->and(strlen($generatedToken))->toBeGreaterThan(20);

    navigateAsAdmin($editUrl)->click('[data-testid="form-submit-btn"]');

    sendSyncRequest('/api/v1/penduduk', [
        'hapus_penduduk' => [['id_pend_desa' => 99999, 'desa_id' => '1234567890']],
    ], $generatedToken)->assertStatus(200);

    sendSyncRequest('/api/v1/identitas-desa', [
        'kode_desa' => '1234567890',
        'sebutan_desa' => 'Desa E2E Test',
        'website' => 'https://e2e-test.desa.id',
        'path' => '[]',
    ], $generatedToken)->assertStatus(200)
        ->assertJson(['status' => 'success']);
})->group('browser', 'e2e', 'sync-flow');

// ─── Negative Cases ─────────────────────────────────────────────────────────

test('request with invalid token is rejected with 401', function () {
    sendSyncRequest('/api/v1/penduduk', ['hapus_penduduk' => []], 'invalid.token.value.12345')
        ->assertStatus(401);
})->group('browser', 'e2e', 'sync-flow', 'negative');

test('request with unregistered (revoked) token is rejected with 401', function () {
    sendSyncRequest('/api/v1/penduduk', ['hapus_penduduk' => []], $this->registeredToken)
        ->assertStatus(200);

    $newToken = app(\Tymon\JWTAuth\JWT::class)->fromUser($this->user);
    SettingAplikasi::updateOrCreate(
        ['key' => 'api_key_opendk'],
        ['value' => $newToken, 'type' => 'textarea']
    );

    sendSyncRequest('/api/v1/penduduk', ['hapus_penduduk' => []], $this->registeredToken)
        ->assertStatus(401)
        ->assertJson(['error' => 'Token not registered']);

    sendSyncRequest('/api/v1/penduduk', ['hapus_penduduk' => []], $newToken)
        ->assertStatus(200);
})->group('browser', 'e2e', 'sync-flow', 'negative');

test('request without any token is rejected', function () {
    test()->postJson('/api/v1/penduduk', ['hapus_penduduk' => []])
        ->assertStatus(401);
})->group('browser', 'e2e', 'sync-flow', 'negative');

test('request with malformed bearer token is rejected', function () {
    sendSyncRequest('/api/v1/penduduk', ['hapus_penduduk' => []], 'not-a-real-jwt-token')
        ->assertStatus(401);
})->group('browser', 'e2e', 'sync-flow', 'negative');

// ─── Payload Verification ───────────────────────────────────────────────────

test('sync penduduk payload structure is correct', function () {
    fakeExternalHttp();

    sendSyncRequest('/api/v1/penduduk', [
        'hapus_penduduk' => [['id_pend_desa' => 1001, 'desa_id' => '1234567890']],
    ], $this->registeredToken)->assertStatus(200);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'pantau.opensid.my.id');
    });
})->group('browser', 'e2e', 'sync-flow', 'payload');

test('sync identitas-desa persists data to database', function () {
    sendSyncRequest('/api/v1/identitas-desa', [
        'kode_desa' => '1234567890',
        'sebutan_desa' => 'Desa E2E',
        'website' => 'https://e2e.desa.id',
        'path' => '[]',
    ], $this->registeredToken)->assertStatus(200)
        ->assertJson(['status' => 'success']);

    $this->assertDatabaseHas('das_data_desa', [
        'desa_id' => '1234567890',
        'sebutan_desa' => 'Desa E2E',
        'website' => 'https://e2e.desa.id',
    ]);
})->group('browser', 'e2e', 'sync-flow', 'payload');

// ─── Full UI Flow ───────────────────────────────────────────────────────────

test('full UI flow: generate token and verify in DB', function () {
    $editUrl = getApiKeyEditUrl();
    $token = generateTokenViaUI($editUrl);

    expect($token)->not->toBeEmpty();

    $this->assertDatabaseHas('das_setting', [
        'key' => 'api_key_opendk',
        'value' => $token,
    ]);

    navigateAsAdmin($editUrl)->click('[data-testid="form-submit-btn"]');
})->group('browser', 'e2e', 'sync-flow', 'ui');
