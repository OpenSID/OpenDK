<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggaranDesa extends Model
{
    protected $table = 'das_anggaran_desa';

    protected $fillable = [
        'desa_id',
        'no_akun',
        'nama_akun',
        'jumlah',
        'bulan',
        'tahun',
    ];

    public function desa()
    {
        return $this->hasOne(DataDesa::class, 'desa_id', 'desa_id');
    }
}
