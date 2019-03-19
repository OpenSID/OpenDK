<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaProgram extends Model
{
    //
    protected $table = 'das_peserta_program';

    protected $fillable = [
        'peserta',
        'program_id',
        'sasaran',
        'no_id_kartu',
        'kartu_nik',
        'kartu_nama',
        'kartu_tempat_lahir',
        'kartu_tanggal_lahir',
        'kartu_alamat',
        'kartu_peserta',
    ];


    public function penduduk()
    {
        if($this->sasaran==1){
            return $this->hasOne(Penduduk::class, 'nik', 'peserta');
        }elseif($this->sasaran==2){
            return $this->hasOne(Penduduk::class, 'no_kk', 'peserta');
        }
    }
}
