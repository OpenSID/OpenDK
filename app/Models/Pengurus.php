<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    protected $table = 'das_pengurus';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $with = [
        'jabatan'
    ];

    public function jabatan()
    {
        return $this->hasOne(DataDesa::class, 'jabatan_id', 'id');
    }
}
