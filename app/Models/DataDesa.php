<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDesa extends Model
{
    protected $table = 'das_data_desa';

    protected $fillable = [
        'desa_id',
        'nama',
        'website',
        'luas_wilayah',
    ];
}
