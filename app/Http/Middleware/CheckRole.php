<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class CheckRole
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
        if (! $request->user()->hasRole('data-entry')) {
            return response( 'Unauthorized.', 401 );
        }

        return $next($request);
    }
}
