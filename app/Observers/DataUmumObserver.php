<?php

namespace App\Observers;

use App\Models\DataUmum;
use App\Services\ActivityLogService;
use App\Services\CacheService;

class DataUmumObserver
{
    public function creating(DataUmum $dataUmum): void
    {
        // New DataUmum creation
    }

    public function created(DataUmum $dataUmum): void
    {
        $this->clearProfilApiCache();

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
        $this->clearProfilApiCache();

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
        $this->clearProfilApiCache();

        ActivityLogService::logAttributeChange(
            'hapus data umum',
            'deleted',
            auth()->id() ?? null,
            $dataUmum->getOriginal()
        );
    }

    /**
     * Hapus cache API profil (yang memuat relasi dataUmum) agar website tidak menampilkan data lama.
     */
    protected function clearProfilApiCache(): void
    {
        app(CacheService::class)->removeCachePrefix(config('theme-api.profil.cache_prefix', 'profil:api'));
    }
}
