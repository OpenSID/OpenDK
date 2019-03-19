<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class SentinelRedirectIfAuthenticated
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
        if ( Sentinel::check() ) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
