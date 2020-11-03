<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterVisitor extends Model
{
    protected $table = 'das_counter_visitor';

    protected $fillable = ['visitor'];

    public $timestamps = false;

    public function pages()
    {
        return $this->belongsToMany('App\Models\Page', 'das_counter_page_visitor', 'visitor_id', 'page_id');
    }
}
