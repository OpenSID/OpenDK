<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $table = 'ref_pekerjaan';

    protected $fillable = ['nama'];
}
