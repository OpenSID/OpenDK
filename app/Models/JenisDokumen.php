<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    use HasFactory;

    use Sluggable;

    protected $table = 'das_jenis_dokumen';

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

    public function formDokumen()
    {
        return $this->hasMany(FormDokumen::class, 'jenis_dokumen_id');
    }

    public function getLinkAttribute(): string
    {
        return  Str::replaceFirst(url('/'), '', route('unduhan.form-dokumen.jenis-dokumen', ['slug' => $this->slug]));
    }
}
