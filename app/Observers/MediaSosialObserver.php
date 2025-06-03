<?php

namespace App\Observers;

use App\Models\MediaSosial;
use Illuminate\Support\Facades\Storage;

class MediaSosialObserver
{
    /**
     * Handle the MediaSosial "created" event.
     *
     * @param  \App\Models\MediaSosial  $mediaSosial
     * @return void
     */
    public function created(MediaSosial $mediaSosial)
    {
        //
    }

    /**
     * Handle the MediaSosial "updated" event.
     *
     * @param  \App\Models\MediaSosial  $mediaSosial
     * @return void
     */
    public function updated(MediaSosial $mediaSosial)
    {
        if ($mediaSosial->isDirty('logo')) {
            Storage::disk('public')->delete($mediaSosial->getOriginal('logo'));
        }
    }

    /**
     * Handle the MediaSosial "deleted" event.
     *
     * @param  \App\Models\MediaSosial  $mediaSosial
     * @return void
     */
    public function deleted(MediaSosial $mediaSosial)
    {
        if (!is_null($mediaSosial->logo)) {
            Storage::disk('public')->delete($mediaSosial->logo);
        }
    }

    /**
     * Handle the MediaSosial "restored" event.
     *
     * @param  \App\Models\MediaSosial  $mediaSosial
     * @return void
     */
    public function restored(MediaSosial $mediaSosial)
    {
        //
    }

    /**
     * Handle the MediaSosial "force deleted" event.
     *
     * @param  \App\Models\MediaSosial  $mediaSosial
     * @return void
     */
    public function forceDeleted(MediaSosial $mediaSosial)
    {
        //
    }
}
