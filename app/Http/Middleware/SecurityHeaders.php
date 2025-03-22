<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    protected $unwantedHeaders = ['X-Powered-By', 'server', 'Server'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if (app()->environment('production')) {
            $localDomain = env('APP_URL', 'http://localhost');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            // $response->headers->set('Content-Security-Policy', "default-src 'self';script-src 'self' https://pantau.opensid.my.id/ https://cdnjs.cloudflare.com/ajax/libs/tinymce/ https://cdn.jsdelivr.net/npm/ platform.twitter.com unpkg.com 'unsafe-inline' 'unsafe-eval';style-src 'self' https://www.tiny.cloud/ http://www.tinymce.com/css/codepen.min.css https://cdnjs.cloudflare.com/ajax/libs/tinymce/ https://cdn.jsdelivr.net/npm/ fonts.googleapis.com unpkg.com 'unsafe-inline';img-src 'self' * data:;font-src 'self' https://cdnjs.cloudflare.com/ajax/libs/tinymce/ https://cdn.jsdelivr.net/npm/ fonts.gstatic.com data:;connect-src 'self' https://pantau.opensid.my.id/ ;media-src 'self';frame-src 'self' platform.twitter.com github.com *.youtube.com *.vimeo.com *.opensid.my.id;object-src 'none' ".$localDomain.";base-uri 'self';report-uri");
        }

        return $response;
    }
}
