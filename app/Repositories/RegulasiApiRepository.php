<?php

namespace App\Repositories;

use App\Models\Regulasi;
use Spatie\QueryBuilder\AllowedFilter;

class RegulasiApiRepository extends BaseApiRepository
{
    /**
     * Constructor
     */
    public function __construct(Regulasi $model)
    {
        parent::__construct($model);
        $this->allowedFilters = [
            'judul',
            'deskripsi',
            'tipe_regulasi',
            AllowedFilter::exact('kecamatan_id'),
            AllowedFilter::exact('tipe.id'),
            AllowedFilter::exact('id'),
        ];
        $this->allowedSorts = [
            'id',
            'judul',
            'tipe_regulasi',
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