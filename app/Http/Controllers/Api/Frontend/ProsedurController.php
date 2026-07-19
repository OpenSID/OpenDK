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

use App\Repositories\ProsedurApiRepository;
use App\Services\CacheService;
use App\Transformers\ProsedurTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Fractal\Fractal;

class ProsedurController extends BaseController
{
    protected ProsedurApiRepository $prosedurApiRepository;
    protected CacheService $cacheService;

    public function __construct(
        ProsedurApiRepository $prosedurApiRepository,
        CacheService $cacheService
    ) {
        $this->prosedurApiRepository = $prosedurApiRepository;
        $this->cacheService = $cacheService;
        $this->prefix = config('theme-api.prosedur.cache_prefix', 'prosedur:api');
    }

    public function index(Request $request): Fractal|JsonResponse
    {
        $params = $request->only(['page', 'per_page', 'filter', 'fields', 'search', 'sort', 'order', 'include']);
        $cacheKey = $this->getCacheKey('index', $params);

        return $this->cacheService->remember($cacheKey, $this->getCacheDuration(), function () use ($request) {
            return $this->fractal($this->prosedurApiRepository->data(), new ProsedurTransformer, 'prosedur');
        }, $this->prefix, 'prosedur');
    }
}
