<?php

namespace App\Observers;

use App\Models\MediaTerkait;
use Illuminate\Support\Facades\Storage;

class MediaTerkaitObserver
{
    /**
     * Handle the MediaTerkait "created" event.
     *
     * @param  \App\Models\MediaTerkait  $mediaTerkait
     * @return void
     */
    public function created(MediaTerkait $mediaTerkait)
    {
        //
    }

    /**
     * Handle the MediaTerkait "updated" event.
     *
     * @param  \App\Models\MediaTerkait  $mediaTerkait
     * @return void
     */
    public function updated(MediaTerkait $mediaTerkait)
    {
        if ($mediaTerkait->isDirty('logo')) {
            Storage::disk('public')->delete($mediaTerkait->getOriginal('logo'));
        }
    }

    /**
     * Handle the MediaTerkait "deleted" event.
     *
     * @param  \App\Models\MediaTerkait  $mediaTerkait
     * @return void
     */
    public function deleted(MediaTerkait $mediaTerkait)
    {
        if (!is_null($mediaTerkait->logo)) {
            Storage::disk('public')->delete($mediaTerkait->logo);
        }
    }

    /**
     * Handle the MediaTerkait "restored" event.
     *
     * @param  \App\Models\MediaTerkait  $mediaTerkait
     * @return void
     */
    public function restored(MediaTerkait $mediaTerkait)
    {
        //
    }

    /**
     * Handle the MediaTerkait "force deleted" event.
     *
     * @param  \App\Models\MediaTerkait  $mediaTerkait
     * @return void
     */
    public function forceDeleted(MediaTerkait $mediaTerkait)
    {
        //
    }
}
