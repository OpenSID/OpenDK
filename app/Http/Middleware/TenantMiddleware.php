<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;

class TenantMiddleware
{
    public function handle($request, Closure $next)
    {        
        $tenantCode = env('KODE_KECAMATAN');
        if (!$tenantCode) {
            Log::debug('TenantMiddleware: No tenant code found, skipping tenant assignment');
            // allow non-tenant contexts for now
            return $next($request);
        }

        $tenant = Tenant::where('kode_kecamatan', $tenantCode)->first(); // Use first() instead of firstOrFail()
        
        // Only set current_tenant if tenant exists
        if ($tenant) {
            app()->instance('current_tenant', $tenant);            
        } else {
            // Log if tenant is not found but don't fail the request
            Log::warning('Tenant not found for code: ' . $tenantCode, [
                'request_path' => $request->path(),
                'request_method' => $request->method()
            ]);
        }
            
        return $next($request);
    }
}
