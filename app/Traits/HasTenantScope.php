<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait HasTenantScope
{

    /**
     * Boot the trait and add global scope for tenant filtering.
     */
    public static function bootHasTenantScope()
    {
        static::addGlobalScope('tenant_scope', function (Builder $builder) {
            // Check if application is installed first
            if (!function_exists('sudahInstal') || !sudahInstal()) {
                return;
            }

            $model = $builder->getModel();
            $table = $model->getTable();
            if (!app()->bound('current_tenant')) {
                $tenantCode = env('KODE_KECAMATAN');
                $tenant = Tenant::where('kode_kecamatan', $tenantCode)->first(); // Use first() instead of firstOrFail()
            } else {
                $tenant = app('current_tenant');
            }

            if (!$tenant) {
                Log::debug('HasTenantScope: No tenant found, skipping scope');
                return;
            }

            $tenantId = $tenant->id;

            // bisa dipastikan setiap tabel ada kolom tenant_id
            try {
                $builder->where($table . '.tenant_id', $tenantId);
            } catch (\Exception $e) {
                // In case schema is not available (e.g., during certain artisan commands), skip scope
                Log::error("HasTenantScope: Error applying tenant scope", [
                    'error' => $e->getMessage(),
                    'model' => get_class($model),
                    'table' => $table,
                    'tenant_id' => $tenantId,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }
        });

        // When creating, automatically set tenant_id if the column exists
        static::creating(function ($model) {
            if (!app()->bound('current_tenant')) {
                $tenantCode = env('KODE_KECAMATAN');
                $tenant = Tenant::where('kode_kecamatan', $tenantCode)->first(); // Use first() instead of firstOrFail()
            } else {
                $tenant = app('current_tenant');
            }

            $model->tenant_id = $tenant->id;
            $model->id = self::getNextIdForTenant($tenant, $model);
        });

        static::deleting(function ($model) {
            if (!app()->bound('current_tenant')) {
                $tenantCode = env('KODE_KECAMATAN');
                $tenant = Tenant::where('kode_kecamatan', $tenantCode)->first(); // Use first() instead of firstOrFail()
            } else {
                $tenant = app('current_tenant');
            }
            if ($tenant) {
                // Pastikan model yang akan dihapus memiliki tenant_id yang sesuai
                if ($model->tenant_id !== $tenant->id) {
                    throw new \Exception('Unauthorized: Cannot delete record from different tenant');
                }
            }
        });
    }

    /**
     * Override delete method to ensure tenant scope is respected
     */
    public function delete()
    {
        if (!app()->bound('current_tenant')) {
            $tenantCode = env('KODE_KECAMATAN');
            $tenant = Tenant::where('kode_kecamatan', $tenantCode)->first(); // Use first() instead of firstOrFail()
        } else {
            $tenant = app('current_tenant');
        }

        if ($tenant) {
            // Hanya lanjutkan penghapusan jika tenant_id cocok
            if ($this->tenant_id !== $tenant->id) {
                throw new \Exception('Unauthorized: Cannot delete record from different tenant');
            }

            // Hapus record hanya jika tenant_id cocok
            return parent::delete();
        }

        // Jika tidak ada tenant, lanjutkan dengan delete biasa
        return parent::delete();
    }

    public static function getNextIdForTenant($tenant, $model): ?int
    {
        $modelClass = get_class($model);
        $table = $model->getTable();
        $tenantId = $tenant->id;

        // We need to query without the global tenant scope to get the max ID for the specific tenant.
        $lastId = $modelClass::withoutGlobalScope('tenant_scope')
            ->where('tenant_id', $tenantId)
            ->max('id');

        if (!is_null($lastId) && $lastId >= $tenant->id_end_range) {
            $errorMessage = 'ID telah mencapai batas akhir range yang diizinkan untuk tenant ini yaitu . ' . $tenant->id_end_range .
                ' Tabel: ' . $table . ', Tenant ID: ' . $tenantId;

            Log::error($errorMessage, [
                'model' => $modelClass,
                'table' => $table,
                'tenant_id' => $tenantId,
                'last_id' => $lastId,
                'id_start_range' => $tenant->id_start_range,
                'id_end_range' => $tenant->id_end_range
            ]);

            throw new \App\Exceptions\TenantIdRangeExceededException($errorMessage);
        }

        $nextId = is_null($lastId) ? $tenant->id_start_range : $lastId + 1;

        return $nextId;
    }
}
