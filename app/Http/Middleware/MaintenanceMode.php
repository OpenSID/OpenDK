<?php

namespace App\Http\Middleware;

use App\Models\SettingAplikasi;
use Closure;

class MaintenanceMode
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
        $maintenance = SettingAplikasi::find(11);
        if ($maintenance) {
            if ($maintenance->value == 1) {
                abort(503);
            }
        }
        
        return $next($request);
    }
}
