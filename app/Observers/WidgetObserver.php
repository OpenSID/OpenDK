<?php

namespace App\Observers;

use App\Models\Widget;
use Illuminate\Support\Facades\Storage;

class WidgetObserver
{
    /**
     * Handle the Widget "created" event.
     *
     * @return void
     */
    public function created(Widget $widget)
    {
        //
    }

    /**
     * Handle the Widget "updated" event.
     *
     * @return void
     */
    public function updated(Widget $widget)
    {
        if ($widget->isDirty('foto')) {
            Storage::disk('local')->delete('public/widget/'.$widget->getOriginal('foto'));
        }
    }

    /**
     * Handle the Widget "deleted" event.
     *
     * @return void
     */
    public function deleted(Widget $widget)
    {
        if (!is_null($widget->foto)) {
            Storage::disk('local')->delete('public/widget/'.$widget->foto);
        }
    }

    /**
     * Handle the Widget "restored" event.
     *
     * @return void
     */
    public function restored(Widget $widget)
    {
        //
    }

    /**
     * Handle the Widget "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Widget $widget)
    {
        //
    }
}
