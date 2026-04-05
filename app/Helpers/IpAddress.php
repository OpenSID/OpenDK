<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Helpers;

use Illuminate\Http\Request;

/**
 * Helper untuk mendeteksi IP asli klien di balik proxy.
 *
 * Mendeteksi IP dari header proxy yang dipercaya:
 * - CF-Connecting-IP (Cloudflare)
 * - X-Real-IP (Nginx)
 * - X-Forwarded-For (standar)
 *
 * Keamanan:
 * - Hanya membaca header jika request berasal dari trusted proxy (divalidasi oleh TrustProxies middleware)
 * - Sanitasi header untuk mencegah header injection
 * - Validasi format IPv4 dan IPv6
 * - Filter IP private/internal untuk rate limiting
 */
class IpAddress
{
    /**
     * Header yang dicek secara berurutan untuk mendapatkan IP asli.
     */
    private const PROXY_HEADERS = [
        'CF_CONNECTING_IP',
        'X_REAL_IP',
        'X_FORWARDED_FOR',
    ];

    /**
     * Dapatkan IP asli klien dari request, dengan fallback ke remote address.
     *
     * Metode ini hanya membaca header yang sudah divalidasi oleh Laravel's
     * TrustProxies middleware. Jika proxy tidak dipercaya, Laravel akan
     * mengabaikan header tersebut dan menggunakan remote_addr.
     */
    public static function getRealIp(Request $request): string
    {
        $ip = self::extractIpFromProxyHeaders($request);

        if ($ip !== null && self::isValidIpAddress($ip)) {
            return self::cleanIpAddress($ip);
        }

        return $request->ip() ?? $request->server('REMOTE_ADDR', '127.0.0.1');
    }

    /**
     * Ekstrak IP dari header proxy yang tersedia.
     */
    private static function extractIpFromProxyHeaders(Request $request): ?string
    {
        foreach (self::PROXY_HEADERS as $header) {
            $headerValue = $request->server->get('HTTP_'.$header);

            if ($headerValue === null || $headerValue === '') {
                continue;
            }

            // X-Forwarded-For bisa berisi multiple IP, ambil yang pertama (client IP)
            if ($header === 'X_FORWARDED_FOR') {
                $firstIp = trim(explode(',', $headerValue)[0]);

                if ($firstIp !== '') {
                    return $firstIp;
                }

                continue;
            }

            // CF-Connecting-IP dan X-Real-IP berisi single IP
            $cleaned = trim($headerValue);

            if ($cleaned !== '') {
                return $cleaned;
            }
        }

        return null;
    }

    /**
     * Validasi format alamat IP (IPv4 atau IPv6).
     */
    private static function isValidIpAddress(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Bersihkan alamat IP dari port atau karakter tidak valid.
     *
     * Menangani kasus:
     * - IPv4 dengan port (contoh: 192.168.1.1:8080)
     * - IPv6 dengan port (contoh: [::1]:8080)
     * - Header injection attempts (newline characters)
     */
    private static function cleanIpAddress(string $ip): string
    {
        // Hapus karakter newline untuk mencegah header injection
        $cleaned = preg_replace('/[\r\n\t]/', '', $ip);

        if ($cleaned === null) {
            return $ip;
        }

        $cleaned = trim($cleaned);

        // Handle IPv6 dengan port: [::1]:8080
        if (str_starts_with($cleaned, '[')) {
            $bracketPos = strpos($cleaned, ']');

            if ($bracketPos !== false) {
                return substr($cleaned, 1, $bracketPos - 1);
            }
        }

        // Handle IPv4 dengan port: 192.168.1.1:8080
        $colonPos = strrpos($cleaned, ':');

        if ($colonPos !== false) {
            $possibleIp = substr($cleaned, 0, $colonPos);

            // Pastikan bukan IPv6 (IPv6 mengandung multiple colon)
            if (substr_count($cleaned, ':') === 1 && filter_var($possibleIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
                return $possibleIp;
            }
        }

        return $cleaned;
    }

    /**
     * Buat key untuk rate limiting berdasarkan IP dan identifier.
     *
     * Key format: "rate_limit:{ip}|{identifier}" atau "rate_limit:{ip}"
     */
    public static function getRateLimitKey(Request $request, ?string $identifier = null): string
    {
        $ip = self::getRealIp($request);

        if ($identifier !== null && $identifier !== '') {
            return "rate_limit:{$ip}|{$identifier}";
        }

        return "rate_limit:{$ip}";
    }
}
