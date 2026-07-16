<?php

use App\Events\RegulasiChanged;
use App\Listeners\ClearRegulasiCacheListener;
use App\Models\Regulasi;
use App\Services\CacheService;

it('clears regulasi cache when event is dispatched', function () {
    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('regulasi:api')
        ->andReturnTrue();

    $this->app->instance(CacheService::class, $cacheService);

    $listener = new ClearRegulasiCacheListener();
    $event = new RegulasiChanged(new Regulasi());

    $listener->handle($event);
});

it('reads correct cache prefix from config', function () {
    config(['theme-api.regulasi.cache_prefix' => 'custom:regulasi']);

    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('custom:regulasi')
        ->andReturnTrue();

    $this->app->instance(CacheService::class, $cacheService);

    $listener = new ClearRegulasiCacheListener();
    $event = new RegulasiChanged(new Regulasi());

    $listener->handle($event);
});

it('handles exception gracefully', function () {
    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->andThrow(new \Exception('Cache error'));

    $this->app->instance(CacheService::class, $cacheService);

    \Illuminate\Support\Facades\Log::shouldReceive('error')->once();

    $listener = new ClearRegulasiCacheListener();
    $event = new RegulasiChanged(new Regulasi());

    $listener->handle($event);
});
