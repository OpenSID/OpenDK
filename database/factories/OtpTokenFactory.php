<?php

namespace Database\Factories;

use App\Models\OtpToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OtpTokenFactory extends Factory
{
    protected $model = OtpToken::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token_hash' => bcrypt('123456'),
            'channel' => $this->faker->randomElement(['email', 'telegram']),
            'identifier' => $this->faker->uuid(),
            'purpose' => $this->faker->randomElement(['login', 'activation', '2fa_login', 'forgot_password']),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes')),
            'attempts' => 0,
        ];
    }
}