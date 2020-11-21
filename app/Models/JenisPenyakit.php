<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPenyakit extends Model
{
    protected $table = 'ref_penyakit';

    protected $fillable = ['nama'];
}
