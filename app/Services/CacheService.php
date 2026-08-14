<?php

namespace App\Services;

use App\Models\CacheKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    private static array $trackedKeys = [];
    
    /**
     * Store cache key and prefix in database for tracking
     *
     * @param string $key Cache key to store
     * @param string|null $prefix Cache prefix (optional)
     * @param string|null $group Group name for organizing cache keys (optional)
     * @return void
     */
    public function storeCacheKey(string $key, ?string $prefix = null, ?string $group = null): void
    {
        try {
            // Check if key is already tracked in memory to avoid unnecessary database queries
            if (isset(self::$trackedKeys[$key])) {
                return;
            }
            
            // Extract prefix from key if not provided
            if ($prefix === null) {
                // If the key contains a colon, take the first part as prefix, otherwise use 'default'
                if (strpos($key, ':') !== false) {
                    $parts = explode(':', $key);
                    $prefix = $parts[0];
                } else {
                    $prefix = 'default';
                }
            }
            
            // Only store the cache key in database if it doesn't already exist
            if (!CacheKey::where('key', $key)->exists()) {
                CacheKey::create([
                    'key' => $key,
                    'prefix' => $prefix,
                    'group' => $group,
                ]);
            }
            
            // Mark key as tracked in memory
            self::$trackedKeys[$key] = true;
        } catch (\Exception $e) {
            // Log error but don't throw exception as it's not critical
            Log::warning('Failed to store cache key in database: ' . $e->getMessage());
        }
    }

    /**
     * Get cache with automatic key tracking
     *
     * @param string $key Cache key
     * @param mixed $default Default value if cache doesn't exist
     * @param string|null $prefix Cache prefix (optional)
     * @param string|null $group Group name (optional)
     * @return mixed
    /**
     * Get cache with automatic key tracking
     *
     * @param string $key Cache key
     * @param mixed $default Default value if cache doesn't exist
     * @param string|null $prefix Cache prefix (optional)
     * @param string|null $group Group name (optional)
     * @return mixed
     */
    public function get(string $key, $default = null, ?string $prefix = null, ?string $group = null)
    {
        return Cache::get($key, $default);
    }

    /**
     * Put cache with automatic key tracking
     *
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param \DateTimeInterface|\DateInterval|int $ttl Time to live
     * @param string|null $prefix Cache prefix (optional)
     * @param string|null $group Group name (optional)
     * @return void
     */
    public function put(string $key, $value, $ttl = 3600, ?string $prefix = null, ?string $group = null): void
    {
        // Store the cache key in database if it doesn't already exist
        $this->storeCacheKey($key, $prefix, $group);
        
        // Handle null TTL for rememberForever
        if ($ttl === null) {
            Cache::forever($key, $value);
        } else {
            Cache::put($key, $value, $ttl);
        }
    }

    /**
     * Remember cache with automatic key tracking
     *
     * @param string $key Cache key
     * @param \DateTimeInterface|\DateInterval|int $ttl Time to live
     * @param \Closure $callback Callback to execute if cache doesn't exist
     * @param string|null $prefix Cache prefix (optional)
     * @param string|null $group Group name (optional)
     * @return mixed
     */
    public function remember(string $key, $ttl, \Closure $callback, ?string $prefix = null, ?string $group = null)
    {
        // Check if cache already exists
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        
        // Cache doesn't exist, so execute callback and store the result
        $value = $callback();
        $this->put($key, $value, $ttl, $prefix, $group);
        
        return $value;
    }

    /**
     * RememberForever cache with automatic key tracking
     *
     * @param string $key Cache key
     * @param \Closure $callback Callback to execute if cache doesn't exist
     * @param string|null $prefix Cache prefix (optional)
     * @param string|null $group Group name (optional)
     * @return mixed
     */
    public function rememberForever(string $key, \Closure $callback, ?string $prefix = null, ?string $group = null)
    {
        // Check if cache already exists
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        
        // Cache doesn't exist, so execute callback and store the result
        $value = $callback();
        $this->put($key, $value, null, $prefix, $group);
        
        return $value;
    }

    /**
     * Remove all cache entries with the specified prefix
     *
     * @param string|null $prefix Cache prefix to clear. If null, uses default prefix
     * @return bool True if successful, false otherwise
     */
    public function removeCachePrefix(?string $prefix = null): bool
    {
        $cachePrefix = $prefix ?? 'theme:api';
        
        try {
            // First, get all cache keys from our database that match the prefix
            $cacheKeys = CacheKey::where('prefix', 'like', "%{$cachePrefix}%")->pluck('key')->toArray();
            
            // Delete these keys from cache
            if (!empty($cacheKeys)) {
                foreach ($cacheKeys as $key) {
                    Cache::forget($key);
                }
                
                // Delete records from our cache_keys table
                CacheKey::where('prefix', 'like', "%{$cachePrefix}%")->delete();
            }
            
            return true;
        } catch (\Exception $e) {
            // Log error if needed
            Log::error('Failed to remove cache by prefix: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Remove specific cache keys by group
     *
     * @param string $group Group name to clear
     * @return bool True if successful, false otherwise
     */
    public function removeCacheByGroup(string $group): bool
    {
        try {
            // Get all cache keys from our database that match the group
            $cacheKeys = CacheKey::where('group', $group)->pluck('key')->toArray();
            
            // Delete these keys from cache
            if (!empty($cacheKeys)) {
                foreach ($cacheKeys as $key) {
                    Cache::forget($key);
                }
                
                // Delete records from our cache_keys table
                CacheKey::where('group', $group)->delete();
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to remove cache by group: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Remove specific cache keys by exact key match
     *
     * @param string $key Key to remove
     * @return bool True if successful, false otherwise
     */
    public function removeCacheByKey(string $key): bool
    {
        try {
            // Remove from Laravel cache
            Cache::forget($key);
            
            // Remove from our cache_keys table
            CacheKey::where('key', $key)->delete();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to remove cache by key: ' . $e->getMessage());
            return false;
        }
    }  
}