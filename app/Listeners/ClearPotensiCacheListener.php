<?php

namespace App\Listeners;

use App\Events\PotensiChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class ClearPotensiCacheListener
{
    public function handle(PotensiChanged $event)
    {
        try {
            $cacheService = app(CacheService::class);
            $prefix = config('theme-api.potensi.cache_prefix', 'potensi:api');
            $cacheService->removeCachePrefix($prefix);
        } catch (\Exception $e) {
            Log::error('Exception occurred while clearing potensi cache', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
