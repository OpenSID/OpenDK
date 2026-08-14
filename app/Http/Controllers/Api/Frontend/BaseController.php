<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Models\SettingAplikasi;
use App\Services\CacheService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    protected $prefix;
    protected function fractal(
        $data,
        null|callable|\League\Fractal\TransformerAbstract $transformer,
        null|string $resourceName = null,
    ): \Spatie\Fractal\Fractal {
        return fractal(
            $data,
            $transformer,
            \League\Fractal\Serializer\JsonApiSerializer::class
        )
            ->withResourceName($resourceName);
    }

    /**
     * Get cache key for Artikel API
     */
    protected function getCacheKey(string $method, array $params = []): string
    {
        $prefix = $this->prefix ?? 'theme:api';
        $key = "{$prefix}:{$method}";
        if (!empty($params)) {
            $key .= ':' . md5(serialize($params));
        }
        return $key;
    }

    /**
     * Get cache duration from config
     */
    protected function getCacheDuration(): int
    {
        return config('theme-api.cache.duration', 3600);
    }

    /**
     * Remove all cache entries with the specified prefix
     *
     * @param string|null $prefix Cache prefix to clear. If null, uses $this->prefix
     * @return bool True if successful, false otherwise
     */
    protected function removeCachePrefix(?string $prefix = null): bool
    {
        $cacheService = app(CacheService::class);
        return $cacheService->removeCachePrefix($prefix ?? $this->prefix);
    }

    protected function isDatabaseGabungan()
    {
        $setting = SettingAplikasi::where('key','sinkronisasi_database_gabungan')->first();
        return ($setting->value ?? null) === '1';
    }
}