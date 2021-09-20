<?php

namespace App\Http\Middleware;

use Closure;
use function file_exists;

use Illuminate\Http\Request;
use function redirect;
use function storage_path;

class KDInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! file_exists(storage_path('installed'))) {
            return redirect()->to('install');
        }
        return $next($request);
    }
}
