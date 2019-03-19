<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    //
    protected $table = 'ref_coa';
    public $incrementing = false;
    public $timestamps= false;

    protected $fillable = [
        'sub_sub_id',
        'coa_name'
    ];
}
