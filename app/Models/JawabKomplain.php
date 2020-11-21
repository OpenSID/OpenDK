<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabKomplain extends Model
{
    protected $table = 'das_jawab_komplain';

    protected $fillable = [
        'komplain_id',
        'penjawab',
        'jawaban',
    ];

    public function komplains()
    {
        return $this->belongsTo(Komplain::class, 'komplain_id', 'komplain_id');
    }

    public function komplain()
    {
        return $this->hasOne(Komplain::class, 'komplain_id', 'komplain_id');
    }

    public function penjawab_komplain()
    {
        return $this->hasOne(Penduduk::class, 'nik', 'penjawab');
    }
}
