<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    protected $table = 'das_komplain';

    protected $fillable = [
        'kategori',
        'nik',
        'nama',
        'judul',
        'slug',
        'laporan',
        'anonim',
        'status',
        'lampiran1',
        'lampiran2',
        'lampiran3',
        'lampiran4',
    ];

    public function kategori_komplain()
    {
        return $this->hasOne(KategoriKomplain::class, 'id', 'kategori');
    }

    public function jawabs()
    {
        return $this->hasMany(JawabKomplain::class, 'komplain_id', 'komplain_id');
    }
}
