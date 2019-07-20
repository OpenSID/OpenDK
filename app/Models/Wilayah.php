<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    public $table = 'ref_wilayah';

    protected $fillable = ['kode', 'nama', 'tahun'];
}
