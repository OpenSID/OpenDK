<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    //
    protected $table = 'das_keluarga';
    public $timestamps = false;

    protected $fillable = [
        'nik_kepala',
        'no_kk',
        'tgl_daftar',
        'tgl_cetak_kk',
        'alamat',
        'dusun',
        'rw',
        'rt'
    ];

    public function cluster()
    {
        return $this->hasOne(WilClusterDesa::class, 'id', 'id_cluster');
    }

    public function kepala_kk()
    {
        return $this->hasOne(Penduduk::class, 'nik_kepala', 'nik');
    }
}
