<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckTenantCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {        
        $tenantCount = Tenant::count();
        if ($tenantCount > 1) {            
            // Return error page if there are more than 1 tenants
            if ($request->is('api/*')) {
                abort(403, 'Akses ditolak: Tidak dapat mengakses pengaturan database dengan lebih dari satu tenant.');
            } else {
                abort(403, 'Akses ditolak: Tidak dapat mengakses pengaturan database dengan lebih dari satu tenant.');
            }
        } 

        return $next($request);
    }
}