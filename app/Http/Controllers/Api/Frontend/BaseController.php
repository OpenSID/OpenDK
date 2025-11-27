<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Models\SettingAplikasi;
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
        $cachePrefix = $prefix ?? $this->prefix ?? 'theme:api';
        
        try {
            $cacheStore = cache();
            $driver = config('cache.default');
            
            // Handle file cache driver
            if ($driver === 'file') {
                return $this->clearFileCache($cachePrefix);
            }
            
            // Handle database cache driver
            if ($driver === 'database') {
                return $this->clearDatabaseCache($cachePrefix);
            }
            
            // Handle array cache driver (simple reset)
            if ($driver === 'array') {
                $cacheStore->flush();
                return true;
            }
            
            // For Redis, Memcached, APC, and other drivers that support pattern matching
            if (method_exists($cacheStore, 'getStore')) {
                $store = $cacheStore->getStore();
                
                // Redis specific
                if (method_exists($store, 'getRedis')) {
                    $redis = $store->getRedis();
                    $keys = $redis->keys("*{$cachePrefix}*");
                    if (!empty($keys)) {
                        $redis->del($keys);
                    }
                    return true;
                }
                
                // Memcached specific
                if (method_exists($store, 'getMemcached')) {
                    // Memcached doesn't support pattern matching directly
                    // We'll need to track keys or use a different approach
                    $cacheStore->flush();
                    return true;
                }
            }
            
            // Fallback: try tags if supported
            if (method_exists($cacheStore, 'tags')) {
                $cacheStore->tags([$cachePrefix])->flush();
                return true;
            }
            
            // Last resort: clear entire cache
            $cacheStore->flush();
            return true;
            
        } catch (\Exception $e) {
            // Log error if needed
            return false;
        }
    }
    
    /**
     * Clear file-based cache with specific prefix
     *
     * @param string $prefix
     * @return bool
     */
    private function clearFileCache(string $prefix): bool
    {
        try {
            $cachePath = config('cache.stores.file.path', storage_path('framework/cache/data'));
            
            if (!is_dir($cachePath)) {
                return true;
            }
            
            $files = glob($cachePath . '/*');
            $prefixPattern = '/' . preg_quote($prefix, '/') . '/';
            
            foreach ($files as $file) {
                if (is_file($file)) {
                    $content = file_get_contents($file);
                    // Check if cache file contains our prefix
                    if (strpos($content, $prefix) !== false) {
                        unlink($file);
                    }
                }
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Clear database-based cache with specific prefix
     *
     * @param string $prefix
     * @return bool
     */
    private function clearDatabaseCache(string $prefix): bool
    {
        try {
            $cacheTable = config('cache.stores.database.table', 'cache');
            
            DB::table($cacheTable)
                ->where('key', 'like', "%{$prefix}%")
                ->delete();
                
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function isDatabaseGabungan()
    {
        $setting = SettingAplikasi::where('key','sinkronisasi_database_gabungan')->first();
        return ($setting->value ?? null) === '1';
    }
}