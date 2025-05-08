<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;

    use Sluggable;
    
    protected $table = 'das_jenis_surat';

    protected $fillable = [
        'nama',
        'slug',
    ];

    /**
     * Return the sluggable configuration array for this model.
    */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama',
            ],
        ];
    }
}
