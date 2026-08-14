<?php

namespace Tests\Traits;

use App\Models\SettingAplikasi;

/**
 * Trait DisableDatabaseGabungan
 *
 * This trait ensures that the sinkronisasi_database_gabungan setting
 * is set to '0' before each test. This is necessary because Pest runs
 * each test closure separately and the Controller loads settings in
 * its constructor.
 */
trait DisableDatabaseGabungan
{
    public function disableDatabaseGabungan(): void
    {
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '0']
        );
    }
}
