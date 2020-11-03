<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormDokumen extends Model
{
    protected $table = 'das_form_dokumen';

    protected $fillable = [
        'nama_dokumen',
        'file_dokumen',
    ];
}
