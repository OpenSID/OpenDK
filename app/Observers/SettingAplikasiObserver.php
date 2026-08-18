<?php

namespace App\Observers;

use App\Models\SettingAplikasi;
use App\Models\User;
use App\Services\ActivityLogService;

class SettingAplikasiObserver
{
    public function creating(SettingAplikasi $setting): void
    {
        // New setting creation
    }

    public function created(SettingAplikasi $setting): void
    {
        if ($setting->wasChanged()) {
            $changed = $setting->getChanges();
            ActivityLogService::logAttributeChange(
                'tambah pengaturan aplikasi',
                'created',
                auth()->id() ?? null,
                $changed,
                subject: $setting,
                causer: auth()->user()
            );
        }
    }

    public function updating(SettingAplikasi $setting): void
    {
        // About to update
    }

    public function updated(SettingAplikasi $setting): void
    {
        if ($setting->wasChanged()) {
            $changed = $setting->getChanges();
            ActivityLogService::logAttributeChange(
                'ubah pengaturan aplikasi',
                'updated',
                auth()->id() ?? null,
                $changed,
                subject: $setting,
                causer: auth()->user()
            );
        }
    }

    public function deleting(SettingAplikasi $setting): void
    {
        // About to delete
    }

    public function deleted(SettingAplikasi $setting): void
    {
        ActivityLogService::logAttributeChange(
            'hapus pengaturan aplikasi',
            'deleted',
            auth()->id() ?? null,
            $setting->getOriginal(),
            subject: $setting,
            causer: auth()->user()
        );
    }
}
