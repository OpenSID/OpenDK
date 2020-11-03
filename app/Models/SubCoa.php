<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCoa extends Model
{
    protected $table     = 'ref_sub_coa';
    public $incrementing = false;

    protected $fillable = [
        'type_id',
        'sub_name',
    ];
}
