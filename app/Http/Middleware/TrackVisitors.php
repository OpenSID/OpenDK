<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;


class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $ip = Request::ip();
        $key = 'online_visitors';

        // Ambil array visitor yang sedang online dari cache
        $visitors = Cache::get($key, []);

        // Masukkan IP ke dalam array jika belum ada
        if (!in_array($ip, $visitors)) {
            $visitors[] = $ip;
        }

        // Simpan kembali array visitor ke cache dengan waktu kedaluwarsa 5 menit
        Cache::put($key, $visitors, now()->addMinutes(5));

        return $next($request);
    }
}
