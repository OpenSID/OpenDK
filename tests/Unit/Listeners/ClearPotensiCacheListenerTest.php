<?php

use App\Events\PotensiChanged;
use App\Listeners\ClearPotensiCacheListener;
use App\Models\Potensi;
use App\Services\CacheService;

it('clears potensi cache when event is dispatched', function () {
    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('potensi:api')
        ->andReturnTrue();

    $this->app->instance(CacheService::class, $cacheService);

    $listener = new ClearPotensiCacheListener();
    $event = new PotensiChanged(new Potensi());

    $listener->handle($event);
});

it('reads correct cache prefix from config', function () {
    config(['theme-api.potensi.cache_prefix' => 'custom:potensi']);

    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('custom:potensi')
        ->andReturnTrue();

    $this->app->instance(CacheService::class, $cacheService);

    $listener = new ClearPotensiCacheListener();
    $event = new PotensiChanged(new Potensi());

    $listener->handle($event);
});

it('handles exception gracefully', function () {
    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->andThrow(new \Exception('Cache error'));

    $this->app->instance(CacheService::class, $cacheService);

    \Illuminate\Support\Facades\Log::shouldReceive('error')->once();

    $listener = new ClearPotensiCacheListener();
    $event = new PotensiChanged(new Potensi());

    $listener->handle($event);
});
