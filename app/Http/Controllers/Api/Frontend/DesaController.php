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

use App\Repositories\DesaApiRepository;
use App\Transformers\DataDesaTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Spatie\Fractal\Fractal;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="OpenDK Desa API",
 *     description="API untuk mengakses data desa",
 *     @OA\Contact(
 *         name="OpenDK Development Team",
 *         email="dev@opendesa.id"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Desa",
 *     description="API endpoints untuk mengelola daftar desa"
 * )
 */

class DesaController extends BaseController
{
    protected DesaApiRepository $desaApiRepository;

    public function __construct(
        DesaApiRepository $desaApiRepository
    ) {
        $this->desaApiRepository = $desaApiRepository;
        $this->prefix = config('theme-api.desa.cache_prefix', 'desa:api');
    }

    /**
     * Display a listing of desa with advanced filtering and sorting.
     *
     * @OA\Get(
     *     path="/api/v1/desa",
     *     summary="Get list of desa",
     *     description="Retrieve paginated list of desa with filtering, sorting, and search capabilities using Spatie Query Builder",
     *     tags={"Desa"},
     *     @OA\Parameter(
     *         name="page[number]",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1, minimum=1)
     *     ),
     *     @OA\Parameter(
     *         name="page[size]",
     *         in="query",
     *         description="Number of items per page (max: 100)",
     *         required=false,
     *         @OA\Schema(type="integer", default=15, minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="type", type="string", example=null),
     *                 @OA\Property(property="id", type="string", example="1"),
     *             @OA\Property(property="attributes", type="object", example={
     *                     "desa_id": "3171011001",
     *                     "kode_desa": "3171011001",
     *                     "nama": "Menteng",
     *                     "sebutan_desa": "Kelurahan",
     *                     "website": "https://menteng.jakarta.go.id",
     *                     "luas_wilayah": "1.23",
     *                     "path": "/menteng"
     *                 })
     *             )),
     *             @OA\Property(property="meta", type="object", example={
     *                 "pagination": {
     *                     "total": 20,
     *                     "count": 20,
     *                     "per_page": 30,
     *                     "current_page": 1,
     *                     "total_pages": 1
     *                 }
     *             }),
     *             @OA\Property(property="links", type="object", example={
     *                 "self": "http://localhost:8000/api/frontend/v1/desa?page[number]=1",
     *                 "first": "http://localhost:8000/api/frontend/v1/desa?page[number]=1",
     *                 "last": "http://localhost:8000/api/frontend/v1/desa?page[number]=1"
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={
     *                 "per_page": ["The per page must not be greater than 100."],
     *                 "sort": ["The selected sort is invalid."]
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function index(Request $request): Fractal|JsonResponse
    {
        $params = $request->only(['page', 'per_page', 'filter', 'fields', 'search', 'sort', 'order', 'include']);
        $cacheKey = $this->getCacheKey('index', $params);
        
        return Cache::remember($cacheKey, $this->getCacheDuration(), function () use ($request) {
            return $this->fractal($this->desaApiRepository->data(), new DataDesaTransformer, 'desa');    
        });
    }    
}