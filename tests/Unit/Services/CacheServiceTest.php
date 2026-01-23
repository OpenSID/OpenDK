<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CacheService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;

class CacheServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected CacheService $cacheService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheService = new CacheService();
        
        // Clear cache before each test
        Cache::flush();
    }

    public function test_store_cache_key_creates_record_in_database(): void
    {
        $key = 'test:cache:key';
        $prefix = 'test';
        $group = 'testing';

        $this->cacheService->storeCacheKey($key, $prefix, $group);

        $this->assertDatabaseHas('cache_keys', [
            'key' => $key,
            'prefix' => $prefix,
            'group' => $group,
        ]);
    }

    public function test_store_cache_key_extract_prefix_from_key_if_not_provided(): void
    {
        $key = 'prefix:another:test:key';
        $expectedPrefix = 'prefix';

        $this->cacheService->storeCacheKey($key);

        $this->assertDatabaseHas('cache_keys', [
            'key' => $key,
            'prefix' => $expectedPrefix,
        ]);
    }

    public function test_store_cache_key_defaults_to_default_prefix_if_no_colon_found(): void
    {
        $key = 'simple_key_without_prefix';
        $expectedPrefix = 'default';

        $this->cacheService->storeCacheKey($key);

        $this->assertDatabaseHas('cache_keys', [
            'key' => $key,
            'prefix' => $expectedPrefix,
        ]);
    }

    public function test_get_method_returns_cache_value(): void
    {
        $key = 'test:get:method';
        $value = 'test_value';

        Cache::put($key, $value, 3600);

        $result = $this->cacheService->get($key);

        $this->assertEquals($value, $result);
        // Note: get() method does not store to database, so we don't assert database presence here
    }

    public function test_put_method_stores_key_and_value_in_cache(): void
    {
        $key = 'test:put:method';
        $value = 'put_test_value';
        $prefix = 'test';
        $group = 'put_test';

        $this->cacheService->put($key, $value, 3600, $prefix, $group);

        $this->assertEquals($value, Cache::get($key));
        $this->assertDatabaseHas('cache_keys', [
            'key' => $key,
            'prefix' => $prefix,
            'group' => $group,
        ]);
    }

    public function test_put_method_with_null_ttl_stores_forever(): void
    {
        $key = 'test:put:forever';
        $value = 'put_forever_value';
        $prefix = 'test';
        $group = 'put_forever_test';

        $this->cacheService->put($key, $value, null, $prefix, $group);

        $this->assertEquals($value, Cache::get($key));
        $this->assertDatabaseHas('cache_keys', [
            'key' => $key,
            'prefix' => $prefix,
            'group' => $group,
        ]);
    }

    public function test_remember_method_stores_key_and_executes_callback_when_cache_missing(): void
    {
        $key = 'test:remember:method';
        $value = 'remembered_value';
        $prefix = 'test';
        $group = 'remember_test';

        $result = $this->cacheService->remember($key, 3600, function () use ($value) {
            return $value;
        }, $prefix, $group);

        $this->assertEquals($value, $result);
        $this->assertEquals($value, Cache::get($key));
        $this->assertDatabaseHas('cache_keys', [
            'key' => $key,
            'prefix' => $prefix,
            'group' => $group,
        ]);
    }

    public function test_remember_method_returns_cached_value_when_exists(): void
    {
        $key = 'test:remember:existing';
        $originalValue = 'original_value';
        $newValue = 'new_value';
        $prefix = 'test';

        // Pre-populate cache
        Cache::put($key, $originalValue, 3600);

        $result = $this->cacheService->remember($key, 3600, function () use ($newValue) {
            return $newValue; // This should not be executed
        }, $prefix);

        $this->assertEquals($originalValue, $result);
        // With the new implementation, the key should NOT be stored in database
        // when cache already exists, as storeCacheKey is only called when cache is new
        $this->assertDatabaseMissing('cache_keys', [
            'key' => $key,
        ]);
    }

    public function test_remember_forever_method_stores_key_and_executes_callback(): void
    {
        $key = 'test:remember:forever';
        $value = 'forever_value';
        $prefix = 'test';
        $group = 'forever_test';

        $result = $this->cacheService->rememberForever($key, function () use ($value) {
            return $value;
        }, $prefix, $group);

        $this->assertEquals($value, $result);
        $this->assertEquals($value, Cache::get($key));
        $this->assertDatabaseHas('cache_keys', [
            'key' => $key,
            'prefix' => $prefix,
            'group' => $group,
        ]);
    }

    public function test_remember_forever_method_returns_cached_value_when_exists(): void
    {
        $key = 'test:remember:forever:existing';
        $originalValue = 'original_value';
        $newValue = 'new_value';
        $prefix = 'test';

        // Pre-populate cache
        Cache::forever($key, $originalValue);

        $result = $this->cacheService->rememberForever($key, function () use ($newValue) {
            return $newValue; // This should not be executed
        }, $prefix);

        $this->assertEquals($originalValue, $result);
        // With the new implementation, the key should NOT be stored in database
        // when cache already exists, as storeCacheKey is only called when cache is new
        $this->assertDatabaseMissing('cache_keys', [
            'key' => $key,
        ]);
    }

    public function test_remove_cache_by_key_removes_specific_key(): void
    {
        $key = 'test:remove:single:key';
        $value = 'test_value';

        Cache::put($key, $value, 3600);
        $this->cacheService->storeCacheKey($key, 'test', 'remove_test');

        $this->assertTrue($this->cacheService->removeCacheByKey($key));
        $this->assertNull(Cache::get($key));
        $this->assertDatabaseMissing('cache_keys', [
            'key' => $key,
        ]);
    }

    public function test_remove_cache_by_group_removes_all_keys_in_group(): void
    {
        $group = 'test_group';
        $keys = [
            'test:group:key1',
            'test:group:key2',
            'test:group:key3',
        ];
        $values = ['value1', 'value2', 'value3'];

        foreach ($keys as $index => $key) {
            Cache::put($key, $values[$index], 3600);
            $this->cacheService->storeCacheKey($key, 'test', $group);
        }

        $this->assertTrue($this->cacheService->removeCacheByGroup($group));

        foreach ($keys as $key) {
            $this->assertNull(Cache::get($key));
        }

        $this->assertDatabaseMissing('cache_keys', [
            'group' => $group,
        ]);
    }

    public function test_remove_cache_prefix_removes_all_keys_with_matching_prefix(): void
    {
        $prefix = 'test:remove:prefix';
        $otherPrefix = 'other:prefix';
        
        $keysWithPrefix = [
            $prefix . ':key1',
            $prefix . ':key2',
            $prefix . ':key3',
        ];
        
        $keysWithOtherPrefix = [
            $otherPrefix . ':key1',
            $otherPrefix . ':key2',
        ];
        
        // Store keys with prefix in our tracking table
        foreach ($keysWithPrefix as $key) {
            $this->cacheService->storeCacheKey($key, $prefix, 'prefix_test');
            Cache::put($key, 'value_for_prefix', 3600);
        }

        // Store keys with other prefix in our tracking table
        foreach ($keysWithOtherPrefix as $key) {
            $this->cacheService->storeCacheKey($key, $otherPrefix, 'prefix_test');
            Cache::put($key, 'value_for_other', 3600);
        }

        // Add some keys that are in cache but not in our tracking table
        Cache::put('non_tracked_key', 'non_tracked_value', 3600);

        $this->assertTrue($this->cacheService->removeCachePrefix($prefix));

        // Keys with the target prefix should be removed from our tracking table
        foreach ($keysWithPrefix as $key) {
            $this->assertDatabaseMissing('cache_keys', [
                'key' => $key,
            ]);
        }

        // Keys with other prefix should still exist in our tracking table
        foreach ($keysWithOtherPrefix as $key) {
            $this->assertDatabaseHas('cache_keys', [
                'key' => $key,
            ]);
        }
    }

    public function test_remove_cache_prefix_with_null_uses_default_prefix(): void
    {
        $defaultPrefix = 'theme:api'; // Based on the implementation
        $keys = [
            $defaultPrefix . ':default1',
            $defaultPrefix . ':default2',
        ];

        foreach ($keys as $key) {
            Cache::put($key, 'value', 3600);
            $this->cacheService->storeCacheKey($key, $defaultPrefix, 'default_test');
        }

        $this->assertTrue($this->cacheService->removeCachePrefix());

        // Since we only remove tracked keys, these should be null
        foreach ($keys as $key) {
            $this->assertNull(Cache::get($key));
        }
        
        // And they should be removed from our tracking table
        foreach ($keys as $key) {
            $this->assertDatabaseMissing('cache_keys', [
                'key' => $key,
            ]);
        }
    }
    
}