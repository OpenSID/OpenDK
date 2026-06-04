<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckActionPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $basePermission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $basePermission)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $user = Auth::user();

        // Super-admin bypass is handled by Gate::before in AuthServiceProvider, 
        // but we can check here explicitly just in case.
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        $action = $this->determineAction($request);
        $requiredPermission = "{$basePermission}.{$action}";

        // Cek apakah permission granular ini exist di database (dari cache/model)
        // Jika modul ini tidak punya aksi spesifik (misal modul lama), fallback ke permission utama
        $permissionExists = \App\Models\Permission::where('name', $requiredPermission)->where('guard_name', 'web')->exists();

        if ($permissionExists) {
            if (! $user->can($requiredPermission)) {
                throw UnauthorizedException::forPermissions([$requiredPermission]);
            }
        } else {
            // Fallback ke permission utama jika permission spesifik aksi tidak ada
            if (! $user->can($basePermission)) {
                throw UnauthorizedException::forPermissions([$basePermission]);
            }
        }

        return $next($request);
    }

    /**
     * Menentukan tipe aksi (view, create, edit, delete, export, import) 
     * berdasarkan method HTTP dan nama route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function determineAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route() ? $request->route()->getName() : '';

        if ($method === 'POST') {
            if (str_contains($routeName, 'import')) {
                return 'import';
            }
            return 'create';
        }

        if (in_array($method, ['PUT', 'PATCH'])) {
            return 'edit';
        }

        if ($method === 'DELETE') {
            return 'delete';
        }

        if ($method === 'GET') {
            if (str_contains($routeName, 'create')) {
                return 'create';
            }
            if (str_contains($routeName, 'edit')) {
                return 'edit';
            }
            if (str_contains($routeName, 'export') || str_contains($routeName, 'cetak') || str_contains($routeName, 'download')) {
                return 'export';
            }
        }

        // Default
        return 'view';
    }
}
