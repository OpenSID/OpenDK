<?php

namespace App\Repositories;

use App\Models\Album;
use Spatie\QueryBuilder\AllowedFilter;

class AlbumApiRepository extends BaseApiRepository
{
    /**
     * Constructor
     */
    public function __construct(Album $model)
    {
        parent::__construct($model);
        $this->allowedFilters = [
            'judul',
            'gambar',
            AllowedFilter::exact('status'),
            AllowedFilter::exact('slug'),
        ];
        $this->allowedSorts = [
            'id',
            'judul',
            'created_at',
            'updated_at'
        ];
        $this->allowedIncludes = ['galeris'];
        $this->defaultSort = '-created_at';
    }


    public function data()
    {
        return $this->getFilteredApi()->jsonPaginate();
    }
}