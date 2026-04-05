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

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

/**
 * Middleware untuk mengatur proxy yang dipercaya
 *
 * Konfigurasi ini penting ketika aplikasi berada di belakang reverse proxy
 * seperti Nginx, Cloudflare, atau load balancer.
 *
 * Secure by default: tidak mempercayai proxy apapun kecuali dikonfigurasi eksplisit.
 *
 * @see https://laravel.com/docs/10.x/requests#configuring-trusted-proxies
 */
class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Secure by default: null berarti tidak trust proxy manapun.
     * Untuk production di belakang reverse proxy, set TRUST_PROXIES
     * ke IP spesifik proxy (comma-separated) atau gunakan Cloudflare IP ranges.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * Header standar yang dipakai Laravel untuk membaca original client IP
     * dari trusted proxy.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Override untuk mendapatkan proxy dari environment variable
     *
     * PENTING: TRUST_PROXIES=* TIDAK diizinkan karena risiko IP spoofing.
     * Jika developer set '*' di environment, akan diabaikan dan return null.
     *
     * @return array<int, string>|string|null
     */
    protected function proxies()
    {
        $envProxies = env('TRUST_PROXIES');

        if ($envProxies === null || $envProxies === '' || $envProxies === '*') {
            // Return null: tidak trust proxy headers dari manapun
            return null;
        }

        // Validasi format IP/CIDR sebelum trust
        $proxies = array_map('trim', explode(',', $envProxies));
        $validProxies = [];

        foreach ($proxies as $proxy) {
            // Validasi IPv4, IPv6, atau CIDR range
            if (filter_var($proxy, FILTER_VALIDATE_IP) || preg_match('/^[\da-fA-F.:]+\/\d+$/', $proxy)) {
                $validProxies[] = $proxy;
            }
        }

        return empty($validProxies) ? null : array_values($validProxies);
    }
}
