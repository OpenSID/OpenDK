<?php

namespace App\Models;


class Survei extends BaseModel
{
    protected $table = 'das_survei';

    protected $fillable = ['session_id', 'response', 'consent'];
}
