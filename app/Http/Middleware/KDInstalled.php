<?php

namespace App\Http\Middleware;

use Closure;

class KDInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! file_exists(storage_path('installed')) ) {

            return redirect()->to( 'install' );
        }
        return $next($request);
    }
}
