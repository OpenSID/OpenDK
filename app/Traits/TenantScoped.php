<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use App\Services\TenantService;

trait TenantScoped
{
    /**
     * Boot the trait for an application.
     *
     * @return void
     */
    public static function bootTenantScoped()
    {
        static::addGlobalScope(new TenantScope());
    }

    /**
     * Get the tenant column name for this model.
     *
     * @return string
     */
    public function getTenantColumn()
    {
        return $this->tenantColumn ?? config('tenant.tenant_column', 'tenant_id');
    }

    /**
     * Query scope to filter records for the current tenant.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCurrentTenant($query)
    {
        $tenantId = TenantService::getCurrentTenantId();

        if ($tenantId) {
            return $query->where($this->getTenantColumn(), $tenantId);
        }

        return $query;
    }

    /**
     * Check if the current model instance belongs to the current tenant.
     *
     * @return bool
     */
    public function belongsToCurrentTenant()
    {
        if (!TenantService::isEnabled()) {
            return true;
        }

        $tenantId = TenantService::getCurrentTenantId();
        $modelTenantId = $this->{$this->getTenantColumn()};

        return $modelTenantId == $tenantId;
    }

    /**
     * Override the newQuery method to ensure tenant filtering.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        $query = parent::newQuery();

        if (!request()->is('api/multi-tenant/setup*')) {
            // Only apply tenant filter if not in setup routes
            $tenantId = TenantService::getCurrentTenantId();

            if ($tenantId) {
                $query->where($this->getTenantColumn(), $tenantId);
            }
        }

        return $query;
    }
}
