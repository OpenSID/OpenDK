<?php

namespace App\Services;

use App\Models\ApiKey;
use App\Models\ApiKeyAuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class KeyService
{
    public function create(array $data, ?int $userId = null): array
    {
        $userId = $userId ?? auth()->id();

        $rawKey = ApiKey::generateKey();

        $apiKey = ApiKey::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'key' => Hash::make($rawKey),
            'key_prefix' => ApiKey::generateKeyPrefix($rawKey),
            'scopes' => $data['scopes'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'status' => ApiKey::STATUS_ACTIVE,
        ]);

        $this->log($apiKey->id, $userId, 'created');

        return [
            'api_key' => $apiKey,
            'raw_key' => $rawKey,
        ];
    }

    public function validate(string $rawKey, ?string $requiredScope = null): array
    {
        $apiKeys = ApiKey::all();

        foreach ($apiKeys as $apiKey) {
            if (!Hash::check($rawKey, $apiKey->key)) {
                continue;
            }

            if ($apiKey->status !== ApiKey::STATUS_ACTIVE) {
                return [
                    'valid' => false,
                    'status' => $apiKey->status,
                    'message' => 'API key is ' . $apiKey->status,
                ];
            }

            if ($apiKey->isExpired()) {
                $apiKey->update(['status' => ApiKey::STATUS_EXPIRED]);
                $this->log($apiKey->id, null, 'validate.expired', [
                    'reason' => 'key_expired',
                ], false);

                return [
                    'valid' => false,
                    'status' => ApiKey::STATUS_EXPIRED,
                    'message' => 'API key has expired',
                ];
            }

            if ($requiredScope !== null && !$apiKey->hasScope($requiredScope)) {
                $this->log($apiKey->id, null, 'validate.insufficient_scope', [
                    'required_scope' => $requiredScope,
                    'key_scopes' => $apiKey->scopes,
                ], false);

                return [
                    'valid' => false,
                    'status' => 'insufficient_scope',
                    'message' => 'API key does not have the required scope',
                ];
            }

            $apiKey->update(['last_used_at' => now()]);

            $this->log($apiKey->id, null, 'validate.success', [
                'scope' => $requiredScope,
            ]);

            return [
                'valid' => true,
                'api_key' => $apiKey,
            ];
        }

        return [
            'valid' => false,
            'status' => 'invalid',
            'message' => 'API key is invalid',
        ];
    }

    public function revoke(int $id, ?int $userId = null): ?ApiKey
    {
        $userId = $userId ?? auth()->id();
        $apiKey = ApiKey::find($id);

        if (!$apiKey) {
            return null;
        }

        $apiKey->update(['status' => ApiKey::STATUS_REVOKED]);
        $this->log($apiKey->id, $userId, 'revoked');

        return $apiKey;
    }

    public function disable(int $id, ?int $userId = null): ?ApiKey
    {
        $userId = $userId ?? auth()->id();
        $apiKey = ApiKey::find($id);

        if (!$apiKey) {
            return null;
        }

        $apiKey->update(['status' => ApiKey::STATUS_DISABLED]);
        $this->log($apiKey->id, $userId, 'disabled');

        return $apiKey;
    }

    public function enable(int $id, ?int $userId = null): ?ApiKey
    {
        $userId = $userId ?? auth()->id();
        $apiKey = ApiKey::find($id);

        if (!$apiKey) {
            return null;
        }

        $apiKey->update(['status' => ApiKey::STATUS_ACTIVE]);
        $this->log($apiKey->id, $userId, 'enabled');

        return $apiKey;
    }

    public function find(string $rawKey): ?ApiKey
    {
        $apiKeys = ApiKey::all();

        foreach ($apiKeys as $apiKey) {
            if (Hash::check($rawKey, $apiKey->key)) {
                return $apiKey;
            }
        }

        return null;
    }

    public function log(
        int $apiKeyId,
        ?int $userId,
        string $action,
        ?array $payload = null,
        bool $success = true,
    ): void {
        try {
            ApiKeyAuditLog::create([
                'api_key_id' => $apiKeyId,
                'user_id' => $userId,
                'action' => $action,
                'payload' => $payload,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'success' => $success,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create API key audit log: ' . $e->getMessage());
        }
    }
}
