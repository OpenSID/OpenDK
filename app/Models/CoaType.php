<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoaType extends Model
{
    //
    protected $table = 'ref_coa_type';
    public $incrementing = false;

    protected $fillable = [
        'type_name'
    ];
}
