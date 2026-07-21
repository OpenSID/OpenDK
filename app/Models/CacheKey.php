<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CacheKey extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'cache_keys';

    protected $fillable = [
        'key',
        'prefix',
        'group',
    ];

    protected $dates = [
        'created_at',
    ];
}
