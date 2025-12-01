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

namespace App\Http\Controllers\Api\Frontend;

use App\Models\Profil;
use App\Repositories\DesaApiRepository;
use App\Repositories\ProfilApiRepository;
use App\Repositories\WebsiteApiRepository;
use App\Transformers\ProfilTransformer;
use App\Transformers\WebsiteTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Fractal;


class WebsiteController extends BaseController
{
    protected ProfilApiRepository $profilApiRepository;
    protected DesaApiRepository $desaApiRepository;
    protected WebsiteApiRepository $websiteApiRepository;

    public function __construct(
        ProfilApiRepository $profilApiRepository,
        DesaApiRepository $desaApiRepository,
        WebsiteApiRepository $websiteApiRepository
    ) {
        $this->profilApiRepository = $profilApiRepository;
        $this->desaApiRepository = $desaApiRepository;
        $this->websiteApiRepository = $websiteApiRepository;
        $this->prefix = config('theme-api.website.cache_prefix', 'website:api');
    }
    public function index(Request $request): Fractal|JsonResponse
    {
        $params = $request->only(['page', 'per_page', 'filter', 'fields', 'search', 'sort', 'order', 'include']);
        $cacheKey = $this->getCacheKey('index', $params);

        return Cache::remember($cacheKey, $this->getCacheDuration(), function () use ($request) {
            $websiteData = $this->websiteApiRepository->getAllWebsiteData();              
            return $this->fractal([
                ['id' => 'profile',  (new ProfilTransformer())->transform(Profil::with(['dataUmum'])->first())],
                ['id' => 'desa',  $this->desaApiRepository->getDataArray()],
                ['id' => 'events',  $websiteData['events']],
                ['id' => 'medsos',  $websiteData['medsos']],
                ['id' => 'media_terkait',  $websiteData['media_terkait']],
                ['id' => 'navigations',  $websiteData['navigations']],
                ['id' => 'navmenus',  $websiteData['navmenus']],
                ['id' => 'pengurus',  $websiteData['pengurus']],
                ['id' => 'sinergi',  $websiteData['sinergi']],
                ['id' => 'slides',  $websiteData['slides']],
                ['id' => 'counter',  $websiteData['counter']],
                ['id' => 'config',  config('profil')],
            ], new WebsiteTransformer());
        });
    }    
}