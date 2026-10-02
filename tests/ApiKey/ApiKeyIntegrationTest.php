<?php

use App\Models\ApiKey;
use App\Models\User;
use App\Services\KeyService;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;

beforeEach(function () {
    $this->keyService = app(KeyService::class);
});

test('can create api key via controller', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->postJson('/api/v1/api-keys', [
        'name' => 'My Test Key',
        'scopes' => ['read', 'write'],
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'api_key' => [
                    'id',
                    'name',
                    'key_prefix',
                    'status',
                    'scopes',
                ],
                'raw_key',
            ],
            'message',
        ]);

    expect($response->json('data.api_key.name'))->toBe('My Test Key');
    expect($response->json('data.api_key.status'))->toBe(ApiKey::STATUS_ACTIVE);
    expect($response->json('data.api_key.scopes'))->toBe(['read', 'write']);
    expect(str_starts_with($response->json('data.raw_key'), 'opendk_'))->toBeTrue();

    $this->assertDatabaseHas('api_keys', [
        'name' => 'My Test Key',
        'user_id' => $this->testUser->id,
    ]);
});

test('can list api keys', function () {
    ApiKey::factory()->count(3)->create([
        'user_id' => $this->testUser->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->getJson('/api/v1/api-keys');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'key_prefix', 'status'],
            ],
        ]);

    expect(count($response->json('data')))->toBe(3);
});

test('can show api key detail', function () {
    $apiKey = ApiKey::factory()->create([
        'user_id' => $this->testUser->id,
        'name' => 'Detail Test Key',
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->getJson("/api/v1/api-keys/{$apiKey->id}");

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Detail Test Key');
});

test('can revoke api key via controller', function () {
    $apiKey = ApiKey::factory()->create([
        'user_id' => $this->testUser->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->postJson("/api/v1/api-keys/{$apiKey->id}/revoke");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'API key revoked successfully',
        ]);

    $this->assertDatabaseHas('api_keys', [
        'id' => $apiKey->id,
        'status' => ApiKey::STATUS_REVOKED,
    ]);
});

test('can delete api key via controller', function () {
    $apiKey = ApiKey::factory()->create([
        'user_id' => $this->testUser->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->deleteJson("/api/v1/api-keys/{$apiKey->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'API key deleted successfully',
        ]);

    $this->assertDatabaseHas('api_keys', [
        'id' => $apiKey->id,
        'status' => ApiKey::STATUS_REVOKED,
    ]);
});

test('cannot access other users api key', function () {
    $otherUser = User::factory()->create();
    $apiKey = ApiKey::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->getJson("/api/v1/api-keys/{$apiKey->id}");

    $response->assertStatus(403);
});

test('cannot revoke other users api key', function () {
    $otherUser = User::factory()->create();
    $apiKey = ApiKey::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->postJson("/api/v1/api-keys/{$apiKey->id}/revoke");

    $response->assertStatus(403);
});

test('middleware rejects missing api key with 401', function () {
    $response = getJson('/api/v1/key/validate');

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Missing API key',
        ]);
});

test('middleware rejects invalid api key with 401', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer opendk_invalid_key_that_does_not_exist',
    ])->getJson('/api/v1/key/validate');

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'API key is invalid',
        ]);
});

test('middleware accepts valid api key', function () {
    $result = $this->keyService->create([
        'name' => 'Middleware Test Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$rawKey}",
    ])->getJson('/api/v1/key/validate');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'API key is valid',
        ]);
});

test('middleware rejects revoked api key with 403', function () {
    $result = $this->keyService->create([
        'name' => 'Revocable Middleware Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $this->keyService->revoke($result['api_key']->id, $this->testUser->id);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$rawKey}",
    ])->getJson('/api/v1/key/validate');

    $response->assertStatus(403)
        ->assertJson([
            'status' => 'revoked',
        ]);
});

test('middleware rejects disabled api key with 403', function () {
    $result = $this->keyService->create([
        'name' => 'Disable Middleware Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $this->keyService->disable($result['api_key']->id, $this->testUser->id);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$rawKey}",
    ])->getJson('/api/v1/key/validate');

    $response->assertStatus(403);
});

test('middleware rejects insufficient scope with 403', function () {
    $result = $this->keyService->create([
        'name' => 'Scoped Middleware Key',
        'scopes' => ['write'],
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$rawKey}",
    ])->getJson('/api/v1/key/validate-scope');

    $response->assertStatus(403)
        ->assertJson([
            'status' => 'insufficient_scope',
        ]);
});

test('middleware accepts valid api key with correct scope', function () {
    $result = $this->keyService->create([
        'name' => 'Scoped Middleware Key',
        'scopes' => ['read'],
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$rawKey}",
    ])->getJson('/api/v1/key/validate');

    $response->assertStatus(200);
});

test('idempotency - duplicate api key creation returns different keys', function () {
    $response1 = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->postJson('/api/v1/api-keys', [
        'name' => 'Idempotent Key',
    ]);

    $response2 = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->postJson('/api/v1/api-keys', [
        'name' => 'Idempotent Key',
    ]);

    $response1->assertStatus(201);
    $response2->assertStatus(201);

    expect($response1->json('data.raw_key'))->not->toBe($response2->json('data.raw_key'));
});

test('create api key validates required fields', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->postJson('/api/v1/api-keys', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

test('create api key validates scopes must be array', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->jwtToken,
    ])->postJson('/api/v1/api-keys', [
        'name' => 'Bad Scope Key',
        'scopes' => 'not-an-array',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['scopes']);
});

test('audit log records api key usage through middleware', function () {
    $result = $this->keyService->create([
        'name' => 'Audit Trail Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $this->withHeaders([
        'Authorization' => "Bearer {$rawKey}",
    ])->getJson('/api/v1/key/validate');

    $this->assertDatabaseHas('api_key_audit_logs', [
        'api_key_id' => $result['api_key']->id,
        'action' => 'validate.success',
        'success' => true,
    ]);
});
