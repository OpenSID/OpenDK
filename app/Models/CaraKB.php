<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaraKB extends Model
{
    protected $table = 'ref_cara_kb';

    protected $fillable = ['nama', 'sex'];
}
