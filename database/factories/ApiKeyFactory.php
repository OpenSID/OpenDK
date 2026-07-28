<?php

namespace Database\Factories;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ApiKeyFactory extends Factory
{
    protected $model = ApiKey::class;

    public function definition(): array
    {
        $rawKey = ApiKey::generateKey();

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(3, true),
            'key' => Hash::make($rawKey),
            'key_prefix' => ApiKey::generateKeyPrefix($rawKey),
            'scopes' => null,
            'expires_at' => null,
            'last_used_at' => null,
            'status' => ApiKey::STATUS_ACTIVE,
        ];
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApiKey::STATUS_REVOKED,
        ]);
    }

    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApiKey::STATUS_DISABLED,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
            'status' => ApiKey::STATUS_EXPIRED,
        ]);
    }

    public function withScope(string|array $scopes): static
    {
        $scopes = is_string($scopes) ? [$scopes] : $scopes;

        return $this->state(fn (array $attributes) => [
            'scopes' => $scopes,
        ]);
    }

    public function withRawKey(string &$rawKey): static
    {
        $rawKey = ApiKey::generateKey();

        return $this->state(fn (array $attributes) => [
            'key' => Hash::make($rawKey),
            'key_prefix' => ApiKey::generateKeyPrefix($rawKey),
        ]);
    }
}
