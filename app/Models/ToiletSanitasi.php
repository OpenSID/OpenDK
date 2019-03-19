<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToiletSanitasi extends Model
{
    //
    protected $table = 'das_toilet_sanitasi';

    protected $fillable = [
        'kecamatan_id',
        'desa_id',
        'toilet',
        'sanitasi',
        'bulan',
        'tahun'
    ];

    public function desa()
    {
        return $this->hasOne(Desa::class, 'id', 'desa_id');
    }
}
