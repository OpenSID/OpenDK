<?php

use App\Services\CacheService;
use App\Models\CacheKey;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

// CacheService Testing
it('can instantiate cache service', function () {
    $service = new CacheService();
    
    expect($service)->toBeInstanceOf(CacheService::class);
});

it('can store cache key in database', function () {
    $service = new CacheService();
    
    $service->storeCacheKey('test_key', 'test_prefix', 'test_group');
    
    expect(CacheKey::where('key', 'test_key')->exists())->toBeTrue();
    expect(CacheKey::where('prefix', 'test_prefix')->exists())->toBeTrue();
    expect(CacheKey::where('group', 'test_group')->exists())->toBeTrue();
});

it('extracts prefix from key if not provided', function () {
    $service = new CacheService();
    
    $service->storeCacheKey('user:123');
    
    expect(CacheKey::where('key', 'user:123')->exists())->toBeTrue();
    expect(CacheKey::where('prefix', 'user')->exists())->toBeTrue();
});

it('uses default prefix if key has no colon', function () {
    $service = new CacheService();
    
    $service->storeCacheKey('simple_key');
    
    expect(CacheKey::where('key', 'simple_key')->exists())->toBeTrue();
    expect(CacheKey::where('prefix', 'default')->exists())->toBeTrue();
});

it('does not store duplicate cache keys', function () {
    // Clear any existing cache keys first
    CacheKey::query()->delete();
    
    // Reset static tracked keys using reflection
    $reflection = new \ReflectionClass(CacheService::class);
    $trackedKeysProperty = $reflection->getProperty('trackedKeys');
    $trackedKeysProperty->setAccessible(true);
    $trackedKeysProperty->setValue(null, []);
    
    $service = new CacheService();
    $service->storeCacheKey('test_key', 'test_prefix');
    
    // Reset tracked keys again to simulate fresh service
    $trackedKeysProperty->setValue(null, []);
    
    $service->storeCacheKey('test_key', 'test_prefix'); // Should not create duplicate
    
    expect(CacheKey::where('key', 'test_key')->count())->toBe(1);
    expect(CacheKey::where('prefix', 'test_prefix')->exists())->toBeTrue();
});

it('does not store already tracked keys in memory', function () {
    // Clear any existing cache keys first
    CacheKey::query()->delete();
    
    // Reset static tracked keys using reflection
    $reflection = new \ReflectionClass(CacheService::class);
    $trackedKeysProperty = $reflection->getProperty('trackedKeys');
    $trackedKeysProperty->setAccessible(true);
    $trackedKeysProperty->setValue(null, []);
    
    $service = new CacheService();
    
    // Manually set a key as tracked
    $trackedKeysProperty->setValue(null, ['test_key' => true]);
    
    $service->storeCacheKey('test_key', 'test_prefix');
    
    expect(CacheKey::count())->toBe(0);
});

it('can get cache value', function () {
    Cache::shouldReceive('get')->once()->with('test_key', 'default_value')->andReturn('cached_value');
    
    $service = new CacheService();
    $result = $service->get('test_key', 'default_value');
    
    expect($result)->toBe('cached_value');
});

it('can put cache value with tracking', function () {
    // Clear any existing cache keys first
    CacheKey::query()->delete();
    
    // Reset static tracked keys using reflection
    $reflection = new \ReflectionClass(CacheService::class);
    $trackedKeysProperty = $reflection->getProperty('trackedKeys');
    $trackedKeysProperty->setAccessible(true);
    $trackedKeysProperty->setValue(null, []);
    
    Cache::shouldReceive('put')->once()->with('test_key', 'test_value', 3600);
    
    $service = new CacheService();
    $service->put('test_key', 'test_value', 3600, 'test_prefix', 'test_group');
    
    expect(CacheKey::where('key', 'test_key')->exists())->toBeTrue();
    expect(CacheKey::where('prefix', 'test_prefix')->exists())->toBeTrue();
    expect(CacheKey::where('group', 'test_group')->exists())->toBeTrue();
});

it('can put cache value forever with tracking', function () {
    // Clear any existing cache keys first
    CacheKey::query()->delete();
    
    // Reset static tracked keys using reflection
    $reflection = new \ReflectionClass(CacheService::class);
    $trackedKeysProperty = $reflection->getProperty('trackedKeys');
    $trackedKeysProperty->setAccessible(true);
    $trackedKeysProperty->setValue(null, []);
    
    Cache::shouldReceive('forever')->once()->with('test_key', 'test_value');
    
    $service = new CacheService();
    $service->put('test_key', 'test_value', null, 'test_prefix', 'test_group');
    
    expect(CacheKey::where('key', 'test_key')->exists())->toBeTrue();
    expect(CacheKey::where('prefix', 'test_prefix')->exists())->toBeTrue();
    expect(CacheKey::where('group', 'test_group')->exists())->toBeTrue();
});

it('can remember cache value', function () {
    // Clear any existing cache keys first
    CacheKey::query()->delete();
    
    // Reset static tracked keys using reflection
    $reflection = new \ReflectionClass(CacheService::class);
    $trackedKeysProperty = $reflection->getProperty('trackedKeys');
    $trackedKeysProperty->setAccessible(true);
    $trackedKeysProperty->setValue(null, []);
    
    Cache::shouldReceive('has')->once()->with('test_key')->andReturn(false);
    Cache::shouldReceive('put')->once()->with('test_key', 'callback_result', 3600);
    
    $service = new CacheService();
    $result = $service->remember('test_key', 3600, function () {
        return 'callback_result';
    }, 'test_prefix', 'test_group');
    
    expect($result)->toBe('callback_result');
    expect(CacheKey::where('key', 'test_key')->exists())->toBeTrue();
    expect(CacheKey::where('prefix', 'test_prefix')->exists())->toBeTrue();
    expect(CacheKey::where('group', 'test_group')->exists())->toBeTrue();
});

it('can remember existing cache value', function () {
    Cache::shouldReceive('has')->once()->with('test_key')->andReturn(true);
    Cache::shouldReceive('get')->once()->with('test_key')->andReturn('existing_value');
    
    $service = new CacheService();
    $result = $service->remember('test_key', 3600, function () {
        return 'callback_result';
    });
    
    expect($result)->toBe('existing_value');
});

it('can remember cache value forever', function () {
    // Clear any existing cache keys first
    CacheKey::query()->delete();
    
    // Reset static tracked keys using reflection
    $reflection = new \ReflectionClass(CacheService::class);
    $trackedKeysProperty = $reflection->getProperty('trackedKeys');
    $trackedKeysProperty->setAccessible(true);
    $trackedKeysProperty->setValue(null, []);
    
    Cache::shouldReceive('has')->once()->with('test_key')->andReturn(false);
    Cache::shouldReceive('forever')->once()->with('test_key', 'callback_result');
    
    $service = new CacheService();
    $result = $service->rememberForever('test_key', function () {
        return 'callback_result';
    }, 'test_prefix', 'test_group');
    
    expect($result)->toBe('callback_result');
    expect(CacheKey::where('key', 'test_key')->exists())->toBeTrue();
    expect(CacheKey::where('prefix', 'test_prefix')->exists())->toBeTrue();
    expect(CacheKey::where('group', 'test_group')->exists())->toBeTrue();
});

it('can remember existing cache value forever', function () {
    Cache::shouldReceive('has')->once()->with('test_key')->andReturn(true);
    Cache::shouldReceive('get')->once()->with('test_key')->andReturn('existing_value');
    
    $service = new CacheService();
    $result = $service->rememberForever('test_key', function () {
        return 'callback_result';
    });
    
    expect($result)->toBe('existing_value');
});

it('can remove cache by prefix', function () {
    // Create test cache keys in database
    CacheKey::factory()->create(['key' => 'theme:api:1', 'prefix' => 'theme:api']);
    CacheKey::factory()->create(['key' => 'theme:api:2', 'prefix' => 'theme:api']);
    CacheKey::factory()->create(['key' => 'user:123', 'prefix' => 'user']);
    
    Cache::shouldReceive('forget')->once()->with('theme:api:1');
    Cache::shouldReceive('forget')->once()->with('theme:api:2');
    
    $service = new CacheService();
    $result = $service->removeCachePrefix('theme:api');
    
    expect($result)->toBeTrue();
    expect(CacheKey::where('prefix', 'theme:api')->exists())->toBeFalse();
    expect(CacheKey::where('prefix', 'user')->exists())->toBeTrue();
});

it('uses default prefix when none provided', function () {
    CacheKey::factory()->create(['key' => 'theme:api:1', 'prefix' => 'theme:api']);
    
    Cache::shouldReceive('forget')->once()->with('theme:api:1');
    
    $service = new CacheService();
    $result = $service->removeCachePrefix();
    
    expect($result)->toBeTrue();
});

it('can remove cache by group', function () {
    // Create test cache keys in database
    CacheKey::factory()->create(['key' => 'key1', 'group' => 'test_group']);
    CacheKey::factory()->create(['key' => 'key2', 'group' => 'test_group']);
    CacheKey::factory()->create(['key' => 'key3', 'group' => 'other_group']);
    
    Cache::shouldReceive('forget')->once()->with('key1');
    Cache::shouldReceive('forget')->once()->with('key2');
    
    $service = new CacheService();
    $result = $service->removeCacheByGroup('test_group');
    
    expect($result)->toBeTrue();
    expect(CacheKey::where('group', 'test_group')->exists())->toBeFalse();
    expect(CacheKey::where('group', 'other_group')->exists())->toBeTrue();
});

it('can remove cache by key', function () {
    // Create test cache key in database
    CacheKey::factory()->create(['key' => 'test_key', 'prefix' => 'test_prefix']);
    
    Cache::shouldReceive('forget')->once()->with('test_key');
    
    $service = new CacheService();
    $result = $service->removeCacheByKey('test_key');
    
    expect($result)->toBeTrue();
    expect(CacheKey::where('key', 'test_key')->exists())->toBeFalse();
});

it('handles errors when removing cache by key', function () {
    Log::shouldReceive('error')->once()->with('Failed to remove cache by key: Database error');
    
    // Mock Cache facade to throw exception
    Cache::shouldReceive('forget')->andThrow(new \Exception('Database error'));
    
    $service = new CacheService();
    $result = $service->removeCacheByKey('test_key');
    
    expect($result)->toBeFalse();
});