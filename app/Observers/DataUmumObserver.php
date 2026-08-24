<?php

namespace App\Observers;

use App\Models\DataUmum;
use App\Services\ActivityLogService;

class DataUmumObserver
{
    public function creating(DataUmum $dataUmum): void
    {
        // New DataUmum creation
    }

    public function created(DataUmum $dataUmum): void
    {
        if ($dataUmum->wasChanged()) {
            $changed = $dataUmum->getChanges();
            ActivityLogService::logAttributeChange(
                'insert data umum',
                'created',
                auth()->id() ?? null,
                $changed
            );
        }
    }

    public function updating(DataUmum $dataUmum): void
    {
        // About to update
    }

    public function updated(DataUmum $dataUmum): void
    {
        if ($dataUmum->wasChanged()) {
            $changed = $dataUmum->getChanges();
            ActivityLogService::logAttributeChange(
                'ubah data umum',
                'updated',
                auth()->id() ?? null,
                $changed
            );
        }
    }

    public function deleting(DataUmum $dataUmum): void
    {
        // About to delete
    }

    public function deleted(DataUmum $dataUmum): void
    {
        ActivityLogService::logAttributeChange(
            'hapus data umum',
            'deleted',
            auth()->id() ?? null,
            $dataUmum->getOriginal()
        );
    }
}