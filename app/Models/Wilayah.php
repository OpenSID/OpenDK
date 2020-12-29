<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use function sprintf;

class Wilayah extends Model
{
    /** @var string konstan panjang kode */
    const PROVINSI  = 2;
    const KABUPATEN = 5;
    const KECAMATAN = 8;
    const DESA      = 13;

    protected $primaryKey = 'kode';

    protected $keyType = 'string';

    public $incrementing = false;

    public $table = 'ref_wilayah';

    protected $fillable = ['kode', 'nama', 'tahun'];

    /**
     * Scope query untuk menampilkan hanya provinsi.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeProvinsi($query)
    {
        return $query->whereRaw(sprintf('LENGTH(kode) = %s', static::PROVINSI));
    }

    /**
     * Scope query untuk menampilkan hanya kabupaten.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeKabupaten($query)
    {
        return $query->whereRaw(sprintf('LENGTH(kode) = %s', static::KABUPATEN));
    }

    /**
     * Scope query untuk menampilkan hanya kecamatan.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeKecamatan($query)
    {
        return $query->whereRaw(sprintf('LENGTH(kode) = %s', static::KECAMATAN));
    }

    /**
     * Scope query untuk menampilkan hanya desa.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDesa($query)
    {
        return $query->whereRaw(sprintf('LENGTH(kode) = %s', static::DESA));
    }

    /**
     * Scope query untuk menampilkan desa berdaskan id kecamatan.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeGetDesaByKecamatan($query, $kecamatan_id)
    {
        return $query->where('kode', 'LIKE', "%{$kecamatan_id}%");
    }
}
