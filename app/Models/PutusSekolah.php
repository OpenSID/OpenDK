<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PutusSekolah extends Model
{
    protected $table    = 'das_putus_sekolah';
    protected $fillable = [
        'kecamatan_id',
        'desa_id',
        'siswa_paud',
        'anak_usia_paud',
        'siswa_sd',
        'anak_usia_sd',
        'siswa_smp',
        'anak_usia_smp',
        'siswa_sma',
        'anak_usia_sma',
        'bulan',
        'tahun',
    ];

    public function desa()
    {
        return $this->hasOne(DataDesa::class, 'desa_id', 'desa_id');
    }
}
