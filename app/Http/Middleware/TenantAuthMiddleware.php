<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantService;
use Symfony\Component\HttpFoundation\Response;

class TenantAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // For authenticated users, verify tenant access
        if (Auth::check()) {
            $user = Auth::user();

            // If user has a pengurus relationship, use kecamatan_id from there
            if ($user->pengurus && $user->pengurus->kecamatan_id) {
                // Set the current tenant ID in the service
                // This will be used by the TenantScope to filter queries
                \Illuminate\Support\Facades\Config::set('tenant.current_tenant_id', $user->pengurus->kecamatan_id);
            }
        }

        return $next($request);
    }
}
