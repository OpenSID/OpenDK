<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table      = "das_jabatan";
    protected $fillable = ['nama_jabatan', 'parent_id',];

    public function childs() {
        return $this->hasMany('App\Models\Jabatan','parent_id','id') ;
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id');
    }
}
