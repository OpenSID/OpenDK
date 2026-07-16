<?php

use App\Events\FormDokumenChanged;
use App\Listeners\ClearFormDokumenCacheListener;
use App\Models\FormDokumen;
use App\Services\CacheService;

it('clears form dokumen cache when event is dispatched', function () {
    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('form_dokumen:api')
        ->andReturnTrue();

    $this->app->instance(CacheService::class, $cacheService);

    $listener = new ClearFormDokumenCacheListener();
    $event = new FormDokumenChanged(new FormDokumen());

    $listener->handle($event);
});

it('reads correct cache prefix from config', function () {
    config(['theme-api.form_dokumen.cache_prefix' => 'custom:prefix']);

    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('custom:prefix')
        ->andReturnTrue();

    $this->app->instance(CacheService::class, $cacheService);

    $listener = new ClearFormDokumenCacheListener();
    $event = new FormDokumenChanged(new FormDokumen());

    $listener->handle($event);
});

it('handles exception gracefully', function () {
    $cacheService = Mockery::mock(CacheService::class);
    $cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->andThrow(new \Exception('Cache error'));

    $this->app->instance(CacheService::class, $cacheService);

    \Illuminate\Support\Facades\Log::shouldReceive('error')->once();

    $listener = new ClearFormDokumenCacheListener();
    $event = new FormDokumenChanged(new FormDokumen());

    // Should not throw exception
    $listener->handle($event);
});
