<?php

namespace App\Observers;

use App\Models\Album;
use App\Services\CacheService;
use Illuminate\Support\Facades\Storage;

class AlbumObserver
{
    /**
     * Handle the Album "created" event.
     *
     * @param  \App\Models\Album  $album
     * @return void
     */
    public function created(Album $album)
    {
        $this->clearCache();
    }

    /**
     * Handle the Album "updated" event.
     *
     * @param  \App\Models\Album  $album
     * @return void
     */
    public function updated(Album $album)
    {
        if ($album->isDirty('gambar')) {
            Storage::disk('public')->delete($album->getOriginal('gambar'));
        }

        $this->clearCache();
    }

    /**
     * Handle the Album "deleted" event.
     *
     * @param  \App\Models\Album  $album
     * @return void
     */
    public function deleted(Album $album)
    {
        if (!is_null($album->gambar)) {
            Storage::disk('public')->delete($album->gambar);
        }

        $this->clearCache();
    }

    /**
     * Handle the Album "restored" event.
     *
     * @param  \App\Models\Album  $album
     * @return void
     */
    public function restored(Album $album)
    {
        //
    }

    /**
     * Handle the Album "force deleted" event.
     *
     * @param  \App\Models\Album  $album
     * @return void
     */
    public function forceDeleted(Album $album)
    {
        //
    }

    protected function clearCache(): void
    {
        try {
            $cacheService = app(CacheService::class);
            $prefix = config('theme-api.album.cache_prefix', 'album:api');
            $cacheService->removeCachePrefix($prefix);
        } catch (\Exception $e) {
            //
        }
    }
}
