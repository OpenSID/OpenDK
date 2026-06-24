<?php

use App\Models\Galeri;
use App\Models\Album;
use App\Services\CacheService;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->cacheService = Mockery::mock(CacheService::class);
    $this->app->instance(CacheService::class, $this->cacheService);
});

it('clears cache when galeri is created', function () {
    $this->cacheService->shouldReceive('removeCachePrefix')
        ->once()
        ->with('galeri:api')
        ->andReturnTrue();

    $album = Album::create([
        'judul' => 'Test Album',
        'gambar' => 'test.jpg',
        'status' => true,
    ]);

    Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Test Galeri',
        'gambar' => ['test.jpg'],
        'jenis' => 'file',
        'status' => true,
        'slug' => 'test-galeri',
    ]);
});

it('clears cache when galeri is updated', function () {
    $this->cacheService->shouldReceive('removeCachePrefix')
        ->with('galeri:api')
        ->andReturnTrue()
        ->twice();

    $album = Album::create([
        'judul' => 'Test Album',
        'gambar' => 'test.jpg',
        'status' => true,
    ]);

    $galeri = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Test Galeri',
        'gambar' => ['test.jpg'],
        'jenis' => 'file',
        'status' => true,
        'slug' => 'test-galeri',
    ]);

    $galeri->update(['judul' => 'Updated Galeri']);
});

it('clears cache when galeri is deleted', function () {
    $this->cacheService->shouldReceive('removeCachePrefix')
        ->with('galeri:api')
        ->andReturnTrue()
        ->twice();

    $album = Album::create([
        'judul' => 'Test Album',
        'gambar' => 'test.jpg',
        'status' => true,
    ]);

    $galeri = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Test Galeri',
        'gambar' => ['test.jpg'],
        'jenis' => 'file',
        'status' => true,
        'slug' => 'test-galeri',
    ]);

    $galeri->delete();
});
