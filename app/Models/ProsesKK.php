<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProsesKK extends Model
{
    protected $table = 'das_proses_kk';

    protected $fillable = [
        'penduduk_id',
        'alamat',
        'tanggal_pengajuan',
        'tanggal_selesai',
        'status',
        'catatan',
    ];

    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'id', 'penduduk_id');
    }
}
