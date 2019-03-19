<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $table = 'das_events';

    protected $fillable = [
        'event_name',
        'start',
        'end',
        'description',
        'attendants',
        'status',
        'attachment'
    ];

    public static function getOpenEvents()
    {
        $events = self::get()->groupBy(function($item)
        {
            return Carbon::parse($item->start)->format('d-M-y');
        });



        return $events;
    }
}