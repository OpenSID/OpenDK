<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FasilitasPAUD extends Model
{
    protected $table    = 'das_fasilitas_paud';
    protected $fillable = [
        'kecamatan_id',
        'desa_id',
        'jumlah_paud',
        'jumlah_guru_paud',
        'jumlah_siswa_paud',
        'bulan',
        'tahun',
    ];

    public function desa()
    {
        return $this->hasOne(DataDesa::class, 'desa_id', 'desa_id');
    }
}
