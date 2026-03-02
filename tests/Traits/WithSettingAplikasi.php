<?php

namespace Tests\Traits;

use App\Models\SettingAplikasi;

trait WithSettingAplikasi
{
    /**
     * Set up default application settings for testing.
     */
    protected function setDefaultApplicationConfig(): void
    {
        // disabled database gabungan for testing
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '0']
        );
    }
}
