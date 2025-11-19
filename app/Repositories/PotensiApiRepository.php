<?php

namespace App\Repositories;

use App\Models\Potensi;
use Spatie\QueryBuilder\AllowedFilter;
use Str;

class PotensiApiRepository extends BaseApiRepository
{
    /**
     * Constructor
     */
    public function __construct(Potensi $model)
    {
        parent::__construct($model);
        $this->allowedFilters = [
            'nama_potensi',
            'deskripsi',
            'lokasi',
            'file_gambar',
            AllowedFilter::exact('kategori_id'),
            AllowedFilter::exact('tipe.slug'),
            AllowedFilter::callback('slug', function($query, $value){
                $query->where('nama_potensi', Str::replace('-',' ',$value));
            })
        ];
        $this->allowedSorts = [
            'id',
            'nama_potensi',
            'lokasi',
            'created_at',
            'updated_at'
        ];
        $this->allowedIncludes = ['tipe'];
        $this->defaultSort = '-created_at';
    }


    public function data()
    {
        return $this->getFilteredApi()->jsonPaginate();
    }
}