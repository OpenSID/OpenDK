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
 * Konfigurasi ini penting ketika aplikasi berada di belakang:
 * - Cloudflare (CDN)
 * - Nginx/Apache sebagai reverse proxy
 * - Load Balancer (AWS ELB, GCP, Azure)
 *
 * @see https://laravel.com/docs/10.x/requests#configuring-trusted-proxies
 */
class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Opsi konfigurasi:
     * 1. '*' - Trust semua proxy (default untuk kemudahan deployment)
     * 2. IP spesifik - Lebih secure untuk production
     * 3. TRUST_PROXIES env var - Dapat di-custom per environment
     *
     * Untuk production, disarankan set IP spesifik di .env:
     * TRUST_PROXIES=103.21.244.0/22,103.22.200.0/22,103.31.4.0/22
     *
     * Daftar IP Cloudflare: https://www.cloudflare.com/ips/
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * Laravel akan membaca IP asli dari header ini:
     * - X-Forwarded-For: Standard de-facto untuk proxy
     * - X-Forwarded-Host: Host asli
     * - X-Forwarded-Port: Port asli
     * - X-Forwarded-Proto: Protocol asli (http/https)
     * - X-Forwarded-AWS-ELB: AWS Load Balancer
     *
     * Catatan: CF-Connecting-IP (Cloudflare) tidak bisa di-set di sini
     * karena tidak ada konstanta di Laravel. Gunakan helper App\Helpers\IpAddress
     * untuk membaca header tersebut.
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
     * @return array<int, string>|string|null
     */
    protected function getTrustedProxies()
    {
        // Cek env variable TRUST_PROXIES
        if ($envProxies = env('TRUST_PROXIES')) {
            if ($envProxies === '*') {
                return '*';
            }

            // Parse comma-separated IP ranges
            return array_map('trim', explode(',', $envProxies));
        }

        // Default: trust all proxies untuk backward compatibility
        return $this->proxies;
    }
}
