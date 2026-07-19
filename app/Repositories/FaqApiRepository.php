<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Models\Faq;
use Spatie\QueryBuilder\AllowedFilter;

class FaqApiRepository extends BaseApiRepository
{
    /**
     * Constructor
     */
    public function __construct(Faq $model)
    {
        parent::__construct($model);
        $this->allowedFilters = [
            'question',
            'answer',
            'status',
        ];
        $this->allowedSorts = [
            'id',
            'question',
            'status',
            'created_at',
            'updated_at'
        ];
        $this->allowedIncludes = [];
        $this->defaultSort = '-created_at';
    }

    public function data()
    {
        return $this->getFilteredApi()
            ->where('status', Status::Aktif)
            ->jsonPaginate();
    }
}