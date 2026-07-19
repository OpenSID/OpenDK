<?php

namespace App\Observers;

use App\Models\User;
use App\Services\PasswordHistoryService;

class UserObserver
{
    public function saving(User $user): void
    {
        if ($user->isDirty('password') && $user->getOriginal('password')) {
            $user->passwordHistories()->create([
                'password' => $user->getOriginal('password'),
            ]);
        }
    }

    public function saved(User $user): void
    {
        if ($user->wasChanged('password')) {
            app(PasswordHistoryService::class)->prune($user);
        }
    }
}
