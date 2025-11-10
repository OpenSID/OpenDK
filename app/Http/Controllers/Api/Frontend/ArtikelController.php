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

use App\Repositories\ArtikelApiRepository;
use App\Transformers\ArtikelTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Fractal;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="OpenDK Artikel API",
 *     description="API untuk mengakses data artikel dengan Spatie Query Builder filtering dan sorting",
 *     @OA\Contact(
 *         name="OpenDK Development Team",
 *         email="dev@opendesa.id"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Artikel",
 *     description="API endpoints untuk mengelola artikel"
 * )
 */

class ArtikelController extends BaseController
{
    protected ArtikelApiRepository $artikelApiRepository;

    public function __construct(
        ArtikelApiRepository $artikelApiRepository
    ) {
        $this->artikelApiRepository = $artikelApiRepository;
        $this->prefix = config('theme-api.artikel.cache_prefix', 'artikel:api');
    }

    /**
     * Display a listing of articles with advanced filtering and sorting.
     *
     * @OA\Get(
     *     path="/api/v1/artikel",
     *     summary="Get list of articles",
     *     description="Retrieve paginated list of articles with filtering, sorting, and search capabilities using Spatie Query Builder",
     *     tags={"Artikel"},
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
     *         name="filter[kategori]",
     *         in="query",
     *         description="Filter by category slug",
     *         required=false,
     *         @OA\Schema(type="string", example="berita")
     *     ),
     *     @OA\Parameter(
     *         name="filter[status]",
     *         in="query",
     *         description="Filter by status (1=published, 0=draft)",
     *         required=false,
     *         @OA\Schema(type="integer", enum={0, 1}, example=1)
     *     ),     
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in title and content fields",
     *         required=false,
     *         @OA\Schema(type="string", example="berita penting")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort field",
     *         required=false,
     *         @OA\Schema(type="string", enum={"created_at", "updated_at", "judul", "id"}, default="created_at")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Sort order",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Include relationships (comma-separated)",
     *         required=false,
     *         @OA\Schema(type="string", example="kategori,comments")
     *     ),
     *     @OA\Parameter(
     *         name="fields",
     *         in="query",
     *         description="Select specific fields (comma-separated)",
     *         required=false,
     *         @OA\Schema(type="string", example="id,judul,slug,created_at")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="type", type="string", example=null),
     *                 @OA\Property(property="id", type="string", example="9"),
     *                 @OA\Property(property="attributes", type="object", example={
     *                     "id_kategori": null,
     *                     "slug": "eveniet-nemo-praesentium-et-dolores-dolor-nemo",
     *                     "judul": "Eveniet nemo praesentium et dolores dolor nemo.",
     *                     "kategori_id": null,
     *                     "gambar": "/storage/artikel//img/no-image.png",
     *                     "isi": "Modi ut voluptate eaque. Pariatur sed et vitae ex velit asperiores neque.",
     *                     "status": 1,
     *                     "created_at": "2025-01-05T14:19:31.000000Z",
     *                     "updated_at": "2025-01-27T08:47:27.000000Z"
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
     *                 "self": "http://localhost:8000/api/frontend/v1/artikel?sort=created_at&page[number]=1",
     *                 "first": "http://localhost:8000/api/frontend/v1/artikel?sort=created_at&page[number]=1",
     *                 "last": "http://localhost:8000/api/frontend/v1/artikel?sort=created_at&page[number]=1"
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
            return $this->fractal($this->artikelApiRepository->data(), new ArtikelTransformer());
        });
    }
    
    /**
     * Display the specified article by ID.
     *
     * @OA\Get(
     *     path="/api/v1/artikel/{id}",
     *     summary="Get single article by ID",
     *     description="Retrieve a single article by its ID",
     *     tags={"Artikel"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Include relationships (comma-separated)",
     *         required=false,
     *         @OA\Schema(type="string", example="kategori,comments")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", example={
     *                 "type": "artikel",
     *                 "id": "9",
     *                 "attributes": {
     *                     "id_kategori": null,
     *                     "slug": "eveniet-nemo-praesentium-et-dolores-dolor-nemo",
     *                     "judul": "Eveniet nemo praesentium et dolores dolor nemo.",
     *                     "kategori_id": null,
     *                     "gambar": "/storage/artikel//img/no-image.png",
     *                     "isi": "Modi ut voluptate eaque. Pariatur sed et vitae ex velit asperiores neque.",
     *                     "status": 1,
     *                     "created_at": "2025-01-05T14:19:31.000000Z",
     *                     "updated_at": "2025-01-27T08:47:27.000000Z"
     *                 }
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={
     *                 "message": "Artikel not found"
     *             })
     *         )
     *     )
     * )
     */
    public function show(int $id, Request $request): Fractal|JsonResponse
    {
        $params = $request->only(['include']);
        $cacheKey = $this->getCacheKey("show:{$id}", $params);

        return Cache::remember($cacheKey, $this->getCacheDuration(), function () use ($id, $request) {
            $artikel = $this->artikelApiRepository->find($id);
            
            if (!$artikel) {
                return response()->json([
                    'errors' => [
                        'message' => 'Artikel not found'
                    ]
                ], 404);
            }

            return $this->fractal($artikel, new ArtikelTransformer());
        });
    }

    /**
     * Display the specified article by slug.
     *
     * @OA\Get(
     *     path="/api/v1/artikel/slug/{slug}",
     *     summary="Get single article by slug",
     *     description="Retrieve a single article by its slug",
     *     tags={"Artikel"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Article slug",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Include relationships (comma-separated)",
     *         required=false,
     *         @OA\Schema(type="string", example="kategori,comments")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function showBySlug(string $slug, Request $request): Fractal|JsonResponse
    {
        $params = $request->only(['include']);
        $cacheKey = $this->getCacheKey("showBySlug:{$slug}", $params);

        return Cache::remember($cacheKey, $this->getCacheDuration(), function () use ($slug, $request) {
            $artikel = $this->artikelApiRepository->findBySlug($slug);
            
            if (!$artikel) {
                return response()->json([
                    'errors' => [
                        'message' => 'Artikel not found'
                    ]
                ], 404);
            }

            return $this->fractal($artikel, new ArtikelTransformer());
        });
    }

    /**
     * Display popular articles.
     *
     * @OA\Get(
     *     path="/api/v1/artikel/popular",
     *     summary="Get popular articles",
     *     description="Retrieve popular articles with optional limit",
     *     tags={"Artikel"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of articles to return (max: 100)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, minimum=1, maximum=100)
     *     ),
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Include relationships (comma-separated)",
     *         required=false,
     *         @OA\Schema(type="string", example="kategori")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function popular(Request $request): Fractal|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $params = $request->only(['limit', 'include']);
        $cacheKey = $this->getCacheKey('popular', $params);

        return Cache::remember($cacheKey, $this->getCacheDuration(), function () use ($request) {
            $limit = $request->get('limit', 10);
            
            // For now, we'll just get the latest articles as "popular"
            // In a real implementation, you might track views or have a popularity metric
            $articles = \App\Models\Artikel::where('status', 1)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return $this->fractal($articles, new ArtikelTransformer());
        });
    }

    /**
     * Display latest articles.
     *
     * @OA\Get(
     *     path="/api/v1/artikel/latest",
     *     summary="Get latest articles",
     *     description="Retrieve latest articles with optional limit",
     *     tags={"Artikel"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of articles to return (max: 100)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, minimum=1, maximum=100)
     *     ),
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Include relationships (comma-separated)",
     *         required=false,
     *         @OA\Schema(type="string", example="kategori")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function latest(Request $request): Fractal|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $params = $request->only(['limit', 'include']);
        $cacheKey = $this->getCacheKey('latest', $params);

        return Cache::remember($cacheKey, $this->getCacheDuration(), function () use ($request) {
            $limit = $request->get('limit', 10);
            
            $articles = \App\Models\Artikel::where('status', 1)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return $this->fractal($articles, new ArtikelTransformer());
        });
    }
}
