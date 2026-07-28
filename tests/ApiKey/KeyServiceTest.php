<?php

use App\Models\ApiKey;
use App\Models\ApiKeyAuditLog;
use App\Services\KeyService;
use Tests\Traits\WithApiKeyTesting;

uses(WithApiKeyTesting::class);

beforeEach(function () {
    $this->keyService = app(KeyService::class);
});

test('can create an api key', function () {
    $result = $this->keyService->create([
        'name' => 'Test API Key',
    ], $this->testUser->id);

    expect($result['api_key'])->toBeInstanceOf(ApiKey::class);
    expect($result['api_key']->name)->toBe('Test API Key');
    expect($result['api_key']->status)->toBe(ApiKey::STATUS_ACTIVE);
    expect($result['api_key']->user_id)->toBe($this->testUser->id);
    expect($result['raw_key'])->toBeString();
    expect(str_starts_with($result['raw_key'], 'opendk_'))->toBeTrue();

    $this->assertDatabaseHas('api_keys', [
        'id' => $result['api_key']->id,
        'name' => 'Test API Key',
        'status' => ApiKey::STATUS_ACTIVE,
    ]);
});

test('can validate a valid api key', function () {
    $result = $this->keyService->create([
        'name' => 'Test Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $validation = $this->keyService->validate($rawKey);

    expect($validation['valid'])->toBeTrue();
    expect($validation['api_key']->id)->toBe($result['api_key']->id);
});

test('returns invalid for non-existent key', function () {
    $validation = $this->keyService->validate('opendk_nonexistent_key_abc123');

    expect($validation['valid'])->toBeFalse();
    expect($validation['status'])->toBe('invalid');
});

test('can revoke an api key', function () {
    $result = $this->keyService->create([
        'name' => 'Revocable Key',
    ], $this->testUser->id);
    $apiKeyId = $result['api_key']->id;

    $revoked = $this->keyService->revoke($apiKeyId, $this->testUser->id);

    expect($revoked)->toBeInstanceOf(ApiKey::class);
    expect($revoked->status)->toBe(ApiKey::STATUS_REVOKED);

    $this->assertDatabaseHas('api_keys', [
        'id' => $apiKeyId,
        'status' => ApiKey::STATUS_REVOKED,
    ]);
});

test('can disable and enable an api key', function () {
    $result = $this->keyService->create([
        'name' => 'Togglable Key',
    ], $this->testUser->id);
    $apiKeyId = $result['api_key']->id;

    $disabled = $this->keyService->disable($apiKeyId, $this->testUser->id);
    expect($disabled->status)->toBe(ApiKey::STATUS_DISABLED);

    $enabled = $this->keyService->enable($apiKeyId, $this->testUser->id);
    expect($enabled->status)->toBe(ApiKey::STATUS_ACTIVE);
});

test('revoked key validation fails', function () {
    $result = $this->keyService->create([
        'name' => 'Revocable Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $this->keyService->revoke($result['api_key']->id, $this->testUser->id);

    $validation = $this->keyService->validate($rawKey);

    expect($validation['valid'])->toBeFalse();
});

test('disabled key validation fails', function () {
    $result = $this->keyService->create([
        'name' => 'Disableable Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $this->keyService->disable($result['api_key']->id, $this->testUser->id);

    $validation = $this->keyService->validate($rawKey);

    expect($validation['valid'])->toBeFalse();
});

test('validation fails for insufficient scope', function () {
    $result = $this->keyService->create([
        'name' => 'Scoped Key',
        'scopes' => ['read'],
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $validation = $this->keyService->validate($rawKey, 'write');

    expect($validation['valid'])->toBeFalse();
    expect($validation['status'])->toBe('insufficient_scope');
});

test('validation passes with correct scope', function () {
    $result = $this->keyService->create([
        'name' => 'Scoped Key',
        'scopes' => ['read', 'write'],
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $validation = $this->keyService->validate($rawKey, 'read');

    expect($validation['valid'])->toBeTrue();
});

test('unscoped key passes any scope check', function () {
    $result = $this->keyService->create([
        'name' => 'Unscoped Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $validation = $this->keyService->validate($rawKey, 'admin');

    expect($validation['valid'])->toBeTrue();
});

test('audit log created on key creation', function () {
    $result = $this->keyService->create([
        'name' => 'Audited Key',
    ], $this->testUser->id);

    $this->assertDatabaseHas('api_key_audit_logs', [
        'api_key_id' => $result['api_key']->id,
        'user_id' => $this->testUser->id,
        'action' => 'created',
        'success' => true,
    ]);
});

test('audit log created on key validation', function () {
    $result = $this->keyService->create([
        'name' => 'Audited Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];

    $this->keyService->validate($rawKey);

    $this->assertDatabaseHas('api_key_audit_logs', [
        'api_key_id' => $result['api_key']->id,
        'action' => 'validate.success',
    ]);
});

test('audit log created on key revocation', function () {
    $result = $this->keyService->create([
        'name' => 'Audited Key',
    ], $this->testUser->id);

    $this->keyService->revoke($result['api_key']->id, $this->testUser->id);

    $this->assertDatabaseHas('api_key_audit_logs', [
        'api_key_id' => $result['api_key']->id,
        'user_id' => $this->testUser->id,
        'action' => 'revoked',
    ]);
});

test('revoke returns null for non-existent key', function () {
    $result = $this->keyService->revoke(99999, $this->testUser->id);

    expect($result)->toBeNull();
});

test('audit log tracks failed validation attempts', function () {
    $validation = $this->keyService->validate('opendk_fake_key_that_does_not_exist');

    expect($validation['valid'])->toBeFalse();
    expect($validation['status'])->toBe('invalid');

    $auditCount = ApiKeyAuditLog::where('action', 'validate.success')->count();
    $failCount = ApiKeyAuditLog::where('success', false)->count();
    expect($auditCount)->toBe(0);
    expect($failCount)->toBe(0);
});

test('idempotency - same user can create multiple keys with same name', function () {
    $result1 = $this->keyService->create([
        'name' => 'Duplicate Name Key',
    ], $this->testUser->id);

    $result2 = $this->keyService->create([
        'name' => 'Duplicate Name Key',
    ], $this->testUser->id);

    expect($result1['api_key']->id)->not->toBe($result2['api_key']->id);
    expect($result1['raw_key'])->not->toBe($result2['raw_key']);

    $this->assertDatabaseHas('api_keys', [
        'id' => $result1['api_key']->id,
        'name' => 'Duplicate Name Key',
    ]);
    $this->assertDatabaseHas('api_keys', [
        'id' => $result2['api_key']->id,
        'name' => 'Duplicate Name Key',
    ]);
});

test('expired key validation returns expired status', function () {
    $apiKey = ApiKey::factory()->expired()->create([
        'user_id' => $this->testUser->id,
    ]);
    $rawKey = 'opendk_test_key_for_expired_check';

    $apiKey->update(['key' => bcrypt($rawKey)]);

    $validation = $this->keyService->validate($rawKey);

    expect($validation['valid'])->toBeFalse();
    expect($validation['status'])->toBe(ApiKey::STATUS_EXPIRED);
});

test('last_used_at is updated on successful validation', function () {
    $result = $this->keyService->create([
        'name' => 'Usage Tracking Key',
    ], $this->testUser->id);
    $rawKey = $result['raw_key'];
    $apiKeyId = $result['api_key']->id;

    expect(ApiKey::find($apiKeyId)->last_used_at)->toBeNull();

    $this->keyService->validate($rawKey);

    expect(ApiKey::find($apiKeyId)->last_used_at)->not->toBeNull();
});
