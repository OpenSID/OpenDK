<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\PasswordHistoryService;

class UserObserver
{
    public function creating(User $user): void
    {
        // New user creation
    }

    public function created(User $user): void
    {
        if ($user->wasChanged()) {
            $changed = $user->getChanges();
            ActivityLogService::logAttributeChange(
                'tambah pengguna',
                'created',
                auth()->id() ?? null,
                $changed
            );
        }
    }

    public function saving(User $user): void
    {
        if ($user->isDirty('password') && $user->getOriginal('password')) {
            $user->passwordHistories()->create([
                'password' => $user->getOriginal('password'),
            ]);
        }
    }

    public function updating(User $user): void
    {
        // About to update
    }

    public function updated(User $user): void
    {
        if ($user->wasChanged()) {
            $changed = $user->getChanges();
            ActivityLogService::logAttributeChange(
                'ubah pengguna',
                'updated',
                auth()->id() ?? null,
                $changed
            );
        }

        if ($user->wasChanged('password')) {
            app(PasswordHistoryService::class)->prune($user);
        }
    }

    public function deleting(User $user): void
    {
        // About to delete
    }

    public function deleted(User $user): void
    {
        ActivityLogService::logAttributeChange(
            'suspend pengguna',
            'deleted',
            auth()->id() ?? null,
            $user->getOriginal()
        );
    }
}