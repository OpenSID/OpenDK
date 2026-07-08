<?php

namespace App\Listeners;

use App\Events\RegulasiChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class ClearRegulasiCacheListener
{
    public function handle(RegulasiChanged $event)
    {
        try {
            $cacheService = app(CacheService::class);
            $prefix = config('theme-api.regulasi.cache_prefix', 'regulasi:api');
            $cacheService->removeCachePrefix($prefix);
        } catch (\Exception $e) {
            Log::error('Exception occurred while clearing regulasi cache', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
