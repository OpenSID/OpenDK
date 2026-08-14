<?php

namespace App\Observers;

use App\Models\Profil;
use App\Services\ActivityLogService;

class ProfilObserver
{
    public function creating(Profil $profil): void
    {
        // New profil creation
    }

    public function created(Profil $profil): void
    {
        if ($profil->wasChanged()) {
            $changed = $profil->getChanges();
            ActivityLogService::logAttributeChange(
                'insert profil',
                'created',
                auth()->id() ?? null,
                $changed
            );
        }
    }

    public function updating(Profil $profil): void
    {
        // About to update
    }

    public function updated(Profil $profil): void
    {
        if ($profil->wasChanged()) {
            $changed = $profil->getChanges();
            ActivityLogService::logAttributeChange(
                'ubah profil',
                'updated',
                auth()->id() ?? null,
                $changed
            );
        }
    }

    public function deleting(Profil $profil): void
    {
        // About to delete
    }

    public function deleted(Profil $profil): void
    {
        ActivityLogService::logAttributeChange(
            'hapus profil',
            'deleted',
            auth()->id() ?? null,
            $profil->getOriginal()
        );
    }
}