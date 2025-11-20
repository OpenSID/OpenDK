<?php

namespace App\Repositories;

use App\Models\FormDokumen;
use Spatie\QueryBuilder\AllowedFilter;

class FormDokumenApiRepository extends BaseApiRepository
{
    /**
     * Constructor
     */
    public function __construct(FormDokumen $model)
    {
        parent::__construct($model);
        $this->allowedFilters = [
            'nama_dokumen',
            'description',
            'jenis_dokumen',
            AllowedFilter::exact('jenis_dokumen_id'),
            AllowedFilter::exact('is_published'),
            AllowedFilter::exact('published_at'),
            AllowedFilter::exact('expired_at'),
            AllowedFilter::callback('aktif', function ($q, $value) {
                if($value){
                    $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
                }                
            })
        ];
        $this->allowedSorts = [
            'id',
            'nama_dokumen',
            'jenis_dokumen',
            'created_at',
            'updated_at',
            'published_at',
            'expired_at'
        ];
        $this->allowedIncludes = [];
        $this->defaultSort = '-created_at';
    }

    public function data()
    {
        return $this->getFilteredApi()->with('jenisDokumen')->jsonPaginate();
    }
}