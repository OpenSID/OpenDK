<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProsesDomisili extends Model
{
    //
    protected $table = 'das_proses_domisili';

    protected $fillable = [
        'penduduk_id',
        'alamat',
        'tanggal_pengajuan',
        'tanggal_selesai',
        'status',
        'catatan'
    ];

    public function penduduk()
    {
        return $this->hasOne(Penduduk::class, 'id','penduduk_id');
    }
}
