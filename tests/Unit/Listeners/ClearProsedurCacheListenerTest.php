<?php

use App\Events\ProsedurChanged;
use App\Listeners\ClearProsedurCacheListener;
use App\Models\Prosedur;
use App\Services\CacheService;

it('clears prosedur cache when event is dispatched', function () {
    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('prosedur:api')
        ->andReturnTrue();

    $this->app->instance(CacheService::class, $cacheService);

    $listener = new ClearProsedurCacheListener();
    $event = new ProsedurChanged(new Prosedur());

    $listener->handle($event);
});

it('reads correct cache prefix from config', function () {
    config(['theme-api.prosedur.cache_prefix' => 'custom:prosedur']);

    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('custom:prosedur')
        ->andReturnTrue();

    $this->app->instance(CacheService::class, $cacheService);

    $listener = new ClearProsedurCacheListener();
    $event = new ProsedurChanged(new Prosedur());

    $listener->handle($event);
});

it('handles exception gracefully', function () {
    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->andThrow(new \Exception('Cache error'));

    $this->app->instance(CacheService::class, $cacheService);

    \Illuminate\Support\Facades\Log::shouldReceive('error')->once();

    $listener = new ClearProsedurCacheListener();
    $event = new ProsedurChanged(new Prosedur());

    // Should not throw exception
    $listener->handle($event);
});
