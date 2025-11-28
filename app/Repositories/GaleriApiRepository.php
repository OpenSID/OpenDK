<?php

namespace App\Repositories;

use App\Models\Galeri;
use Spatie\QueryBuilder\AllowedFilter;

class GaleriApiRepository extends BaseApiRepository
{
    /**
     * Constructor
     */
    public function __construct(Galeri $model)
    {
        parent::__construct($model);
        $this->allowedFilters = [
            'judul',
            'gambar',
            'link',
            AllowedFilter::exact('status'),
            AllowedFilter::exact('album_id'),
            AllowedFilter::exact('album.slug'),
            AllowedFilter::exact('slug'),
            AllowedFilter::exact('jenis'),
        ];
        $this->allowedSorts = [
            'id',
            'judul',
            'created_at',
            'updated_at'
        ];
        $this->allowedIncludes = ['album'];
        $this->defaultSort = '-created_at';
    }


    public function data()
    {
        return $this->getFilteredApi()->jsonPaginate();
    }
}
