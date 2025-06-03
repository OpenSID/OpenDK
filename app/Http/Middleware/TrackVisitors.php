<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * Konsep:
         * 1. Hanya hitung request dengan metode GET. Abaikan semua request selain GET,
         *    termasuk AJAX atau fetch yang menerima response bertipe JSON.
         * 2. Cek di database berdasarkan kolom `ip_address` dan tanggal `visited_at` hari ini.
         *    2a. Jika sudah ada, update kolom `page_views` dan `visited_at`.
         *    2b. Jika belum ada, buat entri baru dengan nilai awal.
         */

        // Hanya metode GET
        if (!$request->isMethod('get')) {
            return $next($request);
        }

        // Deteksi header Accept
        $acceptHeader = $request->header('Accept');
        $isJsonFetch = \Illuminate\Support\Str::contains($acceptHeader, ['application/json', 'text/json']);

        // Deteksi AJAX: jQuery (XMLHttpRequest) atau fetch custom
        $xRequestedWith = $request->header('X-Requested-With');
        $isAjax = $request->ajax() || $xRequestedWith === 'XMLHttpRequest' || $xRequestedWith === 'Fetch';

        // Jika request JSON/fetch/ajax, abaikan
        if ($isJsonFetch || $isAjax) {
            return $next($request);
        }

        // Data tracking
        $ipAddress = $request->ip();
        $hashedIpAddress = hash('sha256', $ipAddress); // Hash IP address
        $url = $request->fullUrl();
        $today = now()->toDateString();
        $userAgent = $request->userAgent();

        $visitor = Visitor::where('ip_address', $hashedIpAddress)
            ->where('url', $url)
            ->whereDate('visited_at', $today)
            ->first();

        if ($visitor) {
            $visitor->increment('page_views');
            $visitor->update(['visited_at' => now()]);
        } else {
            Visitor::create([
                'ip_address' => $hashedIpAddress,
                'url' => $url,
                'user_agent' => $userAgent,
                'visited_at' => now(),
                'page_views' => 1,
            ]);
        }

        return $next($request);
    }
}
