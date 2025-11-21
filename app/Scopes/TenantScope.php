<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Services\TenantService;

class TenantScope implements Scope
{
    /**
     * The tenant column used to identify tenant ownership.
     *
     * @var string
     */
    protected $tenantColumn;

    /**
     * Create a new scope instance.
     *
     * @param  string|null  $tenantColumn
     * @return void
     */
    public function __construct($tenantColumn = null)
    {
        $this->tenantColumn = $tenantColumn ?: config('tenant.kecamatan_column', 'kecamatan_code');
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $tenantId = TenantService::getCurrentTenantId();

        if ($tenantId) {
            $builder->where($this->tenantColumn, $tenantId);
        }
    }
}