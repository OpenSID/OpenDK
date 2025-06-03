<?php

namespace App\Observers;

use App\Models\Album;
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
        //
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
}
