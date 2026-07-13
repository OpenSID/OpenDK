<?php

namespace App\Services;

use App\Models\PasswordHistory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordHistoryService
{
    public const MAX_HISTORY = 10;

    public function isPasswordReused(User $user, string $password): bool
    {
        if (Hash::check($password, $user->password)) {
            return true;
        }

        return $user->passwordHistories()
            ->get()
            ->contains(fn (PasswordHistory $history) => Hash::check($password, $history->password));
    }

    public function storeCurrentPassword(User $user): void
    {
        $user->passwordHistories()->create([
            'password' => $user->password,
        ]);

        $this->prune($user);
    }

    public function prune(User $user): void
    {
        $histories = $user->passwordHistories()
            ->orderBy('created_at', 'desc')
            ->get();

        if ($histories->count() > self::MAX_HISTORY) {
            $histories->slice(self::MAX_HISTORY)->each->delete();
        }
    }
}
