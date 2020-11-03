<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HubunganKeluarga extends Model
{
    protected $table = 'ref_hubungan_keluarga';

    protected $fillable = ['nama'];
}
