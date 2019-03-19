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
        'tahun'
    ];

    public function kecamatan()
    {
        return $this->hasOne(Kecamatan::class, 'id', 'kecamatan_id');
    }

    public function desa()
    {
        return $this->hasOne(Desa::class, 'id', 'desa_id');
    }
}
