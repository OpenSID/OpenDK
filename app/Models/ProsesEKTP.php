<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProsesEKTP extends Model
{
    //
    protected $table = 'das_proses_ektp';

    protected $fillable = [
        'penduduk_id',
        'nik',
        'status_rekam',
        'alamat',
        'tanggal_pengajuan',
        'tanggal_selesai',
        'status'
    ];

    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'id', 'penduduk_id');
    }
}
