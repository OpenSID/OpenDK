<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InstallerCheck
{
    public function handle(Request $request, Closure $next)
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        return $next($request);
    }
}
