<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'das_program';

    protected $fillable = [
        'nama',
        'sasaran',
        'start_date',
        'end_date',
        'description',
    ];

    public function pesertas()
    {
        return $this->hasMany(PesertaProgram::class, 'program_id', 'id');
    }
}
