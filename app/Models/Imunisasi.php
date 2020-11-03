<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imunisasi extends Model
{
    protected $table = 'das_imunisasi';

    protected $fillable = [
        'kecamatan_id',
        'desa_id',
        'cakupan_imunisasi',
        'bulan',
        'tahun',
    ];

    public function kecamatan()
    {
        return $this->hasOne(Wilayah::class, 'kode', 'kecamatan_id');
    }

    public function desa()
    {
        return $this->hasOne(Wilayah::class, 'kode', 'desa_id');
    }
}
