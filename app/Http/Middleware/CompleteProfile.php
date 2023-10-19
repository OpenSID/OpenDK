<?php

namespace App\Http\Middleware;

use App\Models\Profil;
use Closure;

class CompleteProfile
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
        if(!Profil::first()->kecamatan_id) {
            return redirect()->route('data.profil.index');
        }
        
        return $next($request);
    }
}
