<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Potensi extends Model
{
    //
    protected $table = 'das_potensi';

    protected $fillable = [
        'kategori_id',
        'nama_potensi',
        'deskripsi',
        'lokasi',
        'long',
        'lat',
        'file_gambar',
    ];

    public function tipe()
    {
      return $this->hasOne(App\Models\TipePotensi::class, 'id', 'tipe_id');
    }
}
