<?php

namespace Database\Factories;

use App\Models\PasswordHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PasswordHistoryFactory extends Factory
{
    protected $model = PasswordHistory::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'password' => Hash::make($this->faker->password),
        ];
    }
}
