<?php

use App\Models\Album;
use App\Services\CacheService;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->cacheService = Mockery::mock(CacheService::class);
    $this->app->instance(CacheService::class, $this->cacheService);
});

it('clears cache when album is created', function () {
    $this->cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('album:api')
        ->andReturnTrue();

    Album::create([
        'judul' => 'Test Album',
        'gambar' => 'test.jpg',
        'status' => true,
    ]);
});

it('clears cache when album is updated', function () {
    $this->cacheService->shouldReceive('removeCachePrefix')
        ->with('album:api')
        ->andReturnTrue()
        ->twice();

    $album = Album::create([
        'judul' => 'Test Album',
        'gambar' => 'test.jpg',
        'status' => true,
    ]);

    $album->update(['judul' => 'Updated Album']);
});

it('clears cache when album is deleted', function () {
    $this->cacheService->shouldReceive('removeCachePrefix')
        ->with('album:api')
        ->andReturnTrue()
        ->twice();

    $album = Album::create([
        'judul' => 'Test Album',
        'gambar' => 'test.jpg',
        'status' => true,
    ]);

    $album->delete();
});
