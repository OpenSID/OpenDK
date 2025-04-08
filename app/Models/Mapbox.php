<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapbox extends Model
{
    protected $table = 'das_mapbox';
    
    protected $fillable = ['token', 'default_map'];
    public $timestamps = false; // Nonaktifkan timestamps

}
