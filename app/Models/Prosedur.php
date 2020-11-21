<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prosedur extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'das_prosedur';

    protected $fillable = [
        'judul_prosedur',
        'file_prosedur',
        'mime_type',
    ];
}
