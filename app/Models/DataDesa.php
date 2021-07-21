<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDesa extends Model
{
    protected $table = 'das_data_desa';

    protected $fillable = [
        'desa_id',
        'nama',
        'website',
        'luas_wilayah',
    ];

    /**
     * Getter untuk menambahkan url ke /feed.
     * 
     * @return string
     */
    public function getWebsiteUrlFeedAttribute()
    {
        $desa = [
            'desa_id' => $this->desa_id,
            'website' => $this->website . '/index.php/feed'
        ];
        return $desa;
    }

    /**
     * Scope query untuk website desa.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWebsiteUrl($query)
    {
        return $query->whereNotNull('website');
    }
}
