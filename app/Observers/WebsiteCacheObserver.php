<?php

namespace App\Observers;

use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class WebsiteCacheObserver
{
    public function created($model): void
    {
        $this->clearWebsiteCache();
    }

    public function updated($model): void
    {
        $this->clearWebsiteCache();
    }

    public function deleted($model): void
    {
        $this->clearWebsiteCache();
    }

    protected function clearWebsiteCache(): void
    {
        try {
            $prefix = config('theme-api.website.cache_prefix', 'website:api');

            $cacheService = app(CacheService::class);
            $cacheService->removeCachePrefix($prefix);            
        } catch (\Exception $e) {
            //
        }
    }
}
