<?php

namespace App\Models;


class Tenant extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tenants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode_kecamatan',
        'nama_kecamatan',
        'offset_start',
        'offset_end'        
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'offset_start' => 'integer',
        'offset_end' => 'integer'        
    ];    
}