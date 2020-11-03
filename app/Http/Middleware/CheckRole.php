<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use function response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->hasRole('data-entry')) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
