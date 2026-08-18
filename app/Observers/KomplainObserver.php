<?php

namespace App\Observers;

use App\Models\Komplain;
use App\Services\ActivityLogService;

class KomplainObserver
{
    public function creating(Komplain $komplain): void
    {
        // New komplain creation
    }

    public function created(Komplain $komplain): void
    {
        if ($komplain->wasChanged()) {
            $changed = $komplain->getChanges();
            ActivityLogService::logAttributeChange(
                'insert komplain',
                'created',
                auth()->id() ?? null,
                $changed
            );
        }
    }

    public function updating(Komplain $komplain): void
    {
        // About to update
    }

    public function updated(Komplain $komplain): void
    {
        if ($komplain->wasChanged()) {
            $changed = $komplain->getChanges();
            ActivityLogService::logAttributeChange(
                'ubah komplain',
                'updated',
                auth()->id() ?? null,
                $changed
            );
        }
    }

    public function deleting(Komplain $komplain): void
    {
        // About to delete
    }

    public function deleted(Komplain $komplain): void
    {
        ActivityLogService::logAttributeChange(
            'hapus komplain',
            'deleted',
            auth()->id() ?? null,
            $komplain->getOriginal()
        );
    }
}