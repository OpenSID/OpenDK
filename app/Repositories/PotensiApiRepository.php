<?php

namespace App\Repositories;

use App\Models\Potensi;
use Spatie\QueryBuilder\AllowedFilter;

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
            AllowedFilter::exact('kategori_id'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('tipe.id'),
        ];
        $this->allowedSorts = [
            'id',
            'nama_potensi',
            'kategori_id',
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