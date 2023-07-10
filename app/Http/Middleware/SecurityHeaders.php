<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    protected $unwantedHeaders = ['X-Powered-By', 'server', 'Server'];
/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if (app()->environment('production')) {
            $response->headers->set('X-Content-Type-Options', 'nosniff');
        }

        return $response;
    }
}
