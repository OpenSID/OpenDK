<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survei extends Model
{
    protected $table = 'das_survei';

    protected $fillable = ['session_id', 'response', 'consent'];
}
