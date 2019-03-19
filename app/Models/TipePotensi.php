<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipePotensi extends Model
{
    //
    protected $table = 'das_tipe_potensi';
    protected $fillable = [
      'nama_kategori',
      'slug'
    ];
}
