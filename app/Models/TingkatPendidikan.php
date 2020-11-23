<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatPendidikan extends Model
{
    protected $table    = 'das_tingkat_pendidikan';
    protected $fillable = [
        'kecamatan_id',
        'desa_id',
        'semester',
        'tidak_tamat_sekolah',
        'tamat_sd',
        'tamat_smp',
        'tamat_sma',
        'tamat_diploma_sederajat',
        'bulan',
        'tahun',
        'import_id',
    ];

    public function desa()
    {
        return $this->hasOne(DataDesa::class, 'desa_id', 'desa_id');
    }
}
