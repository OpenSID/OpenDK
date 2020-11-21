<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpidemiPenyakit extends Model
{
    protected $table = 'das_epidemi_penyakit';

    protected $fillable = [
        'kecamatan_id',
        'desa_id',
        'jumlah_penderita',
        'penyakit_id',
        'bulan',
        'tahun',
    ];

    public function penyakit()
    {
        return $this->hasOne(JenisPenyakit::class, 'id', 'penyakit_id');
    }

    public function desa()
    {
        return $this->hasOne(Desa::class, 'id', 'desa_id');
    }
}
