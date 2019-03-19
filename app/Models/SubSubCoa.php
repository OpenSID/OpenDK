<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSubCoa extends Model
{
    //
    protected $table = 'ref_sub_sub_coa';
    public $incrementing = false;

    protected $fillable = [
        'sub_id',
        'sub_sub_name'
    ];
}
