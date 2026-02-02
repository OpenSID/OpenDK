<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CacheKey extends Model
{
    use HasFactory;

    protected $table = 'cache_keys';

    protected $fillable = [
        'key',
        'prefix',
        'group',
    ];

    public $timestamps = false;
    
    protected $dates = [
        'created_at',
    ];
}