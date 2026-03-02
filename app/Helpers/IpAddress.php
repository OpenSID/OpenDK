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
use Illuminate\Support\Facades\Log;

/**
 * Helper untuk mendapatkan IP address asli dari request
 *
 * Menangani kasus ketika aplikasi berada di belakang:
 * - Cloudflare Proxy (CDN)
 * - Reverse Proxy (Nginx, Apache, Load Balancer)
 * - AWS ELB/ALB
 *
 * @package App\Helpers
 */
class IpAddress
{
    /**
     * Daftar header yang dicek secara berurutan untuk mendapatkan IP asli
     *
     * Urutan prioritas:
     * 1. CF-Connecting-IP - Cloudflare (paling reliable untuk CF)
     * 2. True-Client-IP - Cloudflare Enterprise
     * 3. X-Forwarded-For - Reverse proxy umum
     * 4. X-Real-IP - Nginx
     * 5. HTTP_X_FORWARDED_FOR - Beberapa proxy lama
     *
     * @var array<int, string>
     */
    private const IP_HEADERS = [
        'CF-Connecting-IP',      // Cloudflare
        'True-Client-IP',        // Cloudflare Enterprise
        'X-Forwarded-For',       // Standard proxy header
        'X-Real-IP',             // Nginx default
        'HTTP_X_FORWARDED_FOR',  // Legacy
    ];

    /**
     * Daftar IP range private yang tidak boleh dianggap sebagai IP asli
     * Kecuali aplikasi memang berjalan di jaringan private
     *
     * @var array<int, string>
     */
    private const PRIVATE_IP_PATTERNS = [
        '10.0.0.0/8',        // 10.0.0.0 - 10.255.255.255
        '172.16.0.0/12',     // 172.16.0.0 - 172.31.255.255
        '192.168.0.0/16',    // 192.168.0.0 - 192.168.255.255
        '127.0.0.0/8',       // 127.0.0.0 - 127.255.255.255 (loopback)
        '169.254.0.0/16',    // 169.254.0.0 - 169.254.255.255 (link-local)
        'fc00::/7',          // IPv6 private
        'fe80::/10',         // IPv6 link-local
        '::1',               // IPv6 loopback
    ];

    /**
     * Mendapatkan IP address asli dari request
     *
     * @param Request $request HTTP Request
     * @param bool $trustPrivateIp Apakah private IP diterima (default: false)
     * @return string IP address yang terdeteksi
     */
    public static function getRealIp(Request $request, bool $trustPrivateIp = false): string
    {
        foreach (self::IP_HEADERS as $header) {
            $ip = self::extractIpFromHeader($request, $header);

            if ($ip === null) {
                continue;
            }

            // Validasi format IP
            if (!self::isValidIpFormat($ip)) {
                Log::warning('Invalid IP format detected', [
                    'header' => $header,
                    'value' => $ip,
                    'request_id' => $request->attributes->get('request_id'),
                ]);
                continue;
            }

            // Cek apakah private IP (jika tidak di-trust)
            if (!$trustPrivateIp && self::isPrivateIp($ip)) {
                // Untuk private IP, lanjutkan ke header berikutnya
                // tapi jika semua header menghasilkan private IP,
                // fallback ke $request->ip() di akhir
                continue;
            }

            return $ip;
        }

        // Fallback ke IP dari request (yang mungkin sudah diproses oleh TrustProxies)
        return $request->ip();
    }

    /**
     * Mengekstrak IP dari header spesifik
     *
     * Handle kasus X-Forwarded-For yang berisi multiple IPs:
     * "client, proxy1, proxy2" -> ambil IP pertama (client)
     *
     * @param Request $request
     * @param string $header
     * @return string|null IP address atau null jika invalid
     */
    private static function extractIpFromHeader(Request $request, string $header): ?string
    {
        $value = $request->header($header);

        if (empty($value)) {
            return null;
        }

        // X-Forwarded-For bisa berupa comma-separated list
        // Format: "client, proxy1, proxy2"
        // Kita ambil IP pertama (original client)
        $ips = explode(',', $value);
        $firstIp = trim($ips[0]);

        // Sanitasi: remove port number jika ada (IPv4:port atau [IPv6]:port)
        $firstIp = self::removePortFromIp($firstIp);

        // Additional sanitasi untuk mencegah injection
        if (strlen($firstIp) > 45) { // IPv6 max length is 45 chars
            return null;
        }

        return $firstIp;
    }

    /**
     * Menghapus port number dari IP address
     *
     * @param string $ip IP with possible port (e.g., "192.168.1.1:8080" or "[::1]:8080")
     * @return string IP without port
     */
    private static function removePortFromIp(string $ip): string
    {
        // Handle IPv6 with port: [::1]:8080
        if (strpos($ip, '[') === 0) {
            $closingBracket = strpos($ip, ']');
            if ($closingBracket !== false) {
                return substr($ip, 1, $closingBracket - 1);
            }
        }

        // Handle IPv4 with port or IPv6 without brackets
        $colonPos = strrpos($ip, ':');
        if ($colonPos !== false) {
            $potentialIp = substr($ip, 0, $colonPos);

            // Cek apakah bagian setelah colon adalah numeric port
            $potentialPort = substr($ip, $colonPos + 1);
            if (ctype_digit($potentialPort)) {
                $ip = $potentialIp;
            }
        }

        return $ip;
    }

    /**
     * Validasi format IP address
     *
     * @param string $ip
     * @return bool
     */
    private static function isValidIpFormat(string $ip): bool
    {
        // Basic sanitasi: karakter yang diperbolehkan
        if (!preg_match('/^[a-fA-F0-9.:]+$/', $ip)) {
            return false;
        }

        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Mengecek apakah IP adalah private IP
     *
     * @param string $ip
     * @return bool
     */
    private static function isPrivateIp(string $ip): bool
    {
        $ipLong = ip2long($ip);

        if ($ipLong === false) {
            // Bukan IPv4, cek IPv6 private ranges
            return self::isPrivateIpv6($ip);
        }

        // Cek IPv4 private ranges
        $privateRanges = [
            ['10.0.0.0', '10.255.255.255'],      // 10.0.0.0/8
            ['172.16.0.0', '172.31.255.255'],    // 172.16.0.0/12
            ['192.168.0.0', '192.168.255.255'],  // 192.168.0.0/16
            ['127.0.0.0', '127.255.255.255'],    // 127.0.0.0/8 (loopback)
        ];

        foreach ($privateRanges as $range) {
            $start = ip2long($range[0]);
            $end = ip2long($range[1]);

            if ($ipLong >= $start && $ipLong <= $end) {
                return true;
            }
        }

        return false;
    }

    /**
     * Mengecek apakah IPv6 adalah private address
     *
     * @param string $ip
     * @return bool
     */
    private static function isPrivateIpv6(string $ip): bool
    {
        // IPv6 private ranges
        $privatePatterns = [
            '/^fc00:/i',     // Unique local addresses (ULA)
            '/^fd/i',        // ULA
            '/^fe80:/i',     // Link-local
            '/^::1$/i',      // Loopback
            '/^fec0:/i',     // Site-local (deprecated)
        ];

        foreach ($privatePatterns as $pattern) {
            if (preg_match($pattern, $ip)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Membuat unique key untuk rate limiting berdasarkan IP dan optional identifier
     *
     * Format: {ip}|{identifier}
     * Contoh: "192.168.1.1|user@example.com"
     *
     * @param Request $request
     * @param string|null $identifier Optional identifier (email, username, dll)
     * @return string Unique key untuk rate limiting
     */
    public static function getRateLimitKey(Request $request, ?string $identifier = null): string
    {
        $ip = self::getRealIp($request);

        if ($identifier) {
            // Sanitasi identifier untuk mencegah collision
            $identifier = strtolower(trim($identifier));
            // Remove karakter berbahaya
            $identifier = preg_replace('/[^a-z0-9@._-]/', '', $identifier);

            return $ip . '|' . $identifier;
        }

        return $ip;
    }
}
