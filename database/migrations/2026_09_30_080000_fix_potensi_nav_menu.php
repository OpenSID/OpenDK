<?php

use App\Models\NavMenu;
use App\Services\CacheService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        NavMenu::where('name', 'Potensi')
            ->where('url', '#')
            ->update([
                'url' => '/potensi',
                'target' => '_self',
                'type' => 'modul',
            ]);

        try {
            app(CacheService::class)->removeCachePrefix(config('theme-api.website.cache_prefix', 'website:api'));
        } catch (\Throwable $e) {
            // Abaikan jika cache service belum siap
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Tidak perlu direverse karena perbaikan data
    }
};
