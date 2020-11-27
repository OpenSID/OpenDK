<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    /** @var string konstan panjang kode */
    const PROVINSI  = 2;
    const KABUPATEN = 5;
    const KECAMATAN = 8;
    const DESA      = 8;

    protected $primaryKey = 'kode';

    protected $keyType = 'string';

    public $incrementing = false;
    
    public $table = 'ref_wilayah';
    
    protected $fillable = ['kode', 'nama', 'tahun'];

    /**
     * Scope query untuk menampilkan hanya provinsi.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProvinsi($query)
    {
        return $query->whereRaw(sprintf('LENGTH(kode) = %s', static::PROVINSI));
    }

    /**
     * Scope query untuk menampilkan hanya provinsi kabupaten.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKabupaten($query)
    {
        return $query->whereRaw(sprintf('LENGTH(kode) = %s', static::KABUPATEN));
    }

    /**
     * Scope query untuk menampilkan hanya kecamatan.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKecamatan($query)
    {
        return $query->whereRaw(sprintf('LENGTH(kode) = %s', static::KECAMATAN));
    }

    /**
     * Scope query untuk menampilkan hanya desa.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDesa($query)
    {
        return $query->whereRaw(sprintf('LENGTH(kode) = %s', static::DESA));
    }

    /**
     * Scope query untuk menampilkan desa berdaskan id kecamatan.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetDesaByKecamatan($query, $kecamatan_id)
    {
        return $query->where('kode', 'LIKE', "%{$kecamatan_id}%");
    }
}
