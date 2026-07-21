<?php

namespace App\Observers;

use App\Models\MediaTerkait;
use Illuminate\Support\Facades\Storage;

class MediaTerkaitObserver
{
    /**
     * Handle the MediaTerkait "created" event.
     *
     * @return void
     */
    public function created(MediaTerkait $mediaTerkait)
    {
        //
    }

    /**
     * Handle the MediaTerkait "updated" event.
     *
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
     * @return void
     */
    public function restored(MediaTerkait $mediaTerkait)
    {
        //
    }

    /**
     * Handle the MediaTerkait "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(MediaTerkait $mediaTerkait)
    {
        //
    }
}
