<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogImport extends Model
{
    protected $table = 'log_imports';

    protected $fillable = [
        'nama_tabel',
        'bulan',
        'tahun',
    ];
}
