<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThemeApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get rate limit configuration
        $rateLimitPerMinute = Config::get('theme-api.rate_limit.per_minute', 120);
        
        // Create rate limiter key based on IP and optional user ID
        $key = $this->resolveRequestSignature($request);
        
        // Check if the request limit has been exceeded
        if (RateLimiter::tooManyAttempts($key, $rateLimitPerMinute)) {
            $response = response()->json([
                'errors' => [
                    'message' => 'Too many requests. Please try again later.',
                    'retry_after' => RateLimiter::availableIn($key)
                ]
            ], 429);
            
            // Add rate limit headers
            $response->headers->set('X-RateLimit-Limit', $rateLimitPerMinute);
            $response->headers->set('X-RateLimit-Remaining', 0);
            $response->headers->set('X-RateLimit-Reset', RateLimiter::availableIn($key));
            
            return $response;
        }
        
        // Hit the rate limiter
        RateLimiter::hit($key);
        
        // Get the response
        $response = $next($request);
        
        // Add rate limit headers to successful responses
        $remaining = $rateLimitPerMinute - RateLimiter::attempts($key);
        $response->headers->set('X-RateLimit-Limit', $rateLimitPerMinute);
        $response->headers->set('X-RateLimit-Remaining', max(0, $remaining));
        $response->headers->set('X-RateLimit-Reset', RateLimiter::availableIn($key));
        
        return $response;
    }
    
    /**
     * Resolve request signature for rate limiting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request): string
    {
        // Use IP address as the primary identifier
        $signature = $request->ip();
        
        // If user is authenticated, include user ID for more specific limiting
        if ($request->user()) {
            $signature .= '|' . $request->user()->id;
        }
        
        // Add API endpoint path for different limits per endpoint
        $signature .= ':' . $request->route()->getName();
        
        return sha1($signature);
    }
}
