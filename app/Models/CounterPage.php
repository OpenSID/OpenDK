<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterPage extends Model
{
    protected $table = 'das_counter_page';

    protected $fillable = ['page'];

    public $timestamps = false;

    public function visitors()
    {
        return $this->belongsToMany(CounterVisitor::class, 'das_counter_page_visitor', 'page_id', 'visitor_id');
    }
}
