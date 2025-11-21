<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Services\TenantService;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Hook into Eloquent model events to handle offset ID generation
        Model::creating(function ($model) {
            // Handle offset ID generation for creating models
            $this->handleOffsetIdGeneration($model, 'creating');
        });

        Model::updating(function ($model) {
            // Handle tenant restrictions on updates
            $this->handleTenantRestriction($model, 'updating');
        });
    }

    /**
     * Handle offset ID generation for models
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $event
     * @return void
     */
    private function handleOffsetIdGeneration($model, $event)
    {
        // For now, we'll implement offset ID logic when we save records
        // This will be refined based on the specific requirements
        $currentTenantId = TenantService::getCurrentTenantId();

        if ($currentTenantId && $event === 'creating') {
            // When creating, we might need to set the tenant column if it exists
            $tenantColumn = $model->getTenantColumn() ?? config('tenant.kecamatan_column', 'kecamatan_id');
            
            if (property_exists($model, $tenantColumn) && !$model->{$tenantColumn}) {
                $model->{$tenantColumn} = $currentTenantId;
            }
        }
    }

    /**
     * Handle tenant restriction checks
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $event
     * @return void
     */
    private function handleTenantRestriction($model, $event)
    {
        $currentTenantId = TenantService::getCurrentTenantId();

        if ($currentTenantId && $model->getKey()) {
            // Check if this model belongs to the current tenant before updating
            if (method_exists($model, 'belongsToCurrentTenant') && !$model->belongsToCurrentTenant()) {
                throw new \Exception('Access denied: This record does not belong to your tenant.');
            }
        }
    }
}