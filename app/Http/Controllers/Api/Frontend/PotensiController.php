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

use App\Repositories\PotensiApiRepository;
use App\Transformers\PotensiTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Spatie\Fractal\Fractal;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="OpenDK Potensi API",
 *     description="API untuk mengakses data potensi",
 *     @OA\Contact(
 *         name="OpenDK Development Team",
 *         email="dev@opendesa.id"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Potensi",
 *     description="API endpoints untuk mengelola potensi"
 * )
 */
 
class PotensiController extends BaseController
{
    protected PotensiApiRepository $potensiApiRepository;

    public function __construct(
        PotensiApiRepository $potensiApiRepository
    ) {
        $this->potensiApiRepository = $potensiApiRepository;
        $this->prefix = config('theme-api.potensi.cache_prefix', 'potensi:api');
    }

    /**
     * Display a listing of potensi with advanced filtering and sorting.
     *
     * @OA\Get(
     *     path="/api/v1/frontend/potensi",
     *     summary="Get list of potensi",
     *     description="Retrieve paginated list of potensi with filtering, sorting, and search capabilities using Spatie Query Builder",
     *     tags={"Potensi"},
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
     *     @OA\Parameter(
     *         name="filter[nama_potensi]",
     *         in="query",
     *         description="Filter potensi by name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter[deskripsi]",
     *         in="query",
     *         description="Filter potensi by description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter[lokasi]",
     *         in="query",
     *         description="Filter potensi by location",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter[kategori_id]",
     *         in="query",
     *         description="Filter potensi by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="type", type="string", example="potensi"),
     *                 @OA\Property(property="id", type="string", example="1"),
     *                 @OA\Property(property="attributes", type="object", example={
     *                     "id": 1,
     *                     "kategori_id": 1,
     *                     "nama_potensi": "Potensi Wisata",
     *                     "deskripsi": "Deskripsi potensi",
     *                     "lokasi": "Lokasi potensi",
     *                     "long": "106.823",
     *                     "lat": "-6.178",
     *                     "file_gambar": "gambar.jpg",
     *                     "gambar_path": "http://localhost:8000/storage/gambar.jpg",
     *                     "slug": "potensi-wisata",
     *                     "created_at": "2023-01-01T00:00:00.0000Z",
     *                     "updated_at": "2023-01-01T00:00:00.000Z"
     *                 })
     *             )),
     *             @OA\Property(property="meta", type="object", example={
     *                 "pagination": {
     *                     "total": 20,
     *                     "count": 20,
     *                     "per_page": 15,
     *                     "current_page": 1,
     *                     "total_pages": 2
     *                 }
     *             }),
     *             @OA\Property(property="links", type="object", example={
     *                 "first": "http://localhost:8000/api/frontend/v1/potensi?page[number]=1",
     *                 "last": "http://localhost:8000/api/frontend/v1/potensi?page[number]=2",
     *                 "prev": null,
     *                 "next": "http://localhost:8000/api/frontend/v1/potensi?page[number]=2"
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={
     *                 "per_page": {"The per page must not be greater than 100."},
     *                 "sort": {"The selected sort is invalid."}
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
            return $this->fractal($this->potensiApiRepository->data(), new PotensiTransformer, 'potensi');    
        });
    }    
}