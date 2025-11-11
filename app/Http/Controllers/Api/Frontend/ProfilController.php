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

use App\Repositories\ProfilApiRepository;
use App\Transformers\ProfilTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Fractal;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="OpenDK Profil API",
 *     description="API untuk mengakses data profil dengan Spatie Query Builder filtering dan sorting",
 *     @OA\Contact(
 *         name="OpenDK Development Team",
 *         email="dev@opendesa.id"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Profil",
 *     description="API endpoints untuk mengelola profil"
 * )
 */

class ProfilController extends BaseController
{
    protected ProfilApiRepository $profilApiRepository;

    public function __construct(
        ProfilApiRepository $profilApiRepository
    ) {
        $this->profilApiRepository = $profilApiRepository;
        $this->prefix = config('theme-api.profil.cache_prefix', 'profil:api');
    }

    /**
     * Display a listing of profiles with advanced filtering and sorting.
     *
     * @OA\Get(
     *     path="/api/v1/profil",
     *     summary="Get list of profiles",
     *     description="Retrieve paginated list of profiles with filtering, sorting, and search capabilities using Spatie Query Builder",
     *     tags={"Profil"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1, minimum=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page (max: 100)",
     *         required=false,
     *         @OA\Schema(type="integer", default=15, minimum=1, maximum=100)
     *     ),
     *     @OA\Parameter(
     *         name="filter[nama_kecamatan]",
     *         in="query",
     *         description="Filter by kecamatan name",
     *         required=false,
     *         @OA\Schema(type="string", example="Jakarta Pusat")
     *     ),
     *     @OA\Parameter(
     *         name="filter[nama_kabupaten]",
     *         in="query",
     *         description="Filter by kabupaten name",
     *         required=false,
     *         @OA\Schema(type="string", example="Jakarta")
     *     ),
     *     @OA\Parameter(
     *         name="filter[nama_provinsi]",
     *         in="query",
     *         description="Filter by provinsi name",
     *         required=false,
     *         @OA\Schema(type="string", example="DKI Jakarta")
     *     ),
     *     @OA\Parameter(
     *         name="filter[kecamatan_id]",
     *         in="query",
     *         description="Filter by kecamatan ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in kecamatan, kabupaten, provinsi, and alamat fields",
     *         required=false,
     *         @OA\Schema(type="string", example="jakarta")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort field",
     *         required=false,
     *         @OA\Schema(type="string", enum={"nama_kecamatan", "nama_kabupaten", "nama_provinsi", "created_at", "updated_at", "id"}, default="nama_kecamatan")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Sort order",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="asc")
     *     ),
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Include relationships (comma-separated)",
     *         required=false,
     *         @OA\Schema(type="string", example="dataUmum,dataDesa")
     *     ),
     *     @OA\Parameter(
     *         name="fields",
     *         in="query",
     *         description="Select specific fields (comma-separated)",
     *         required=false,
     *         @OA\Schema(type="string", example="id,nama_kecamatan,nama_kabupaten,nama_provinsi,alamat")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="type", type="string", example=null),
     *                 @OA\Property(property="id", type="string", example="1"),
     *                 @OA\Property(property="attributes", type="object", example={
     *                     "provinsi_id": 31,
     *                     "nama_provinsi": "DKI Jakarta",
     *                     "kabupaten_id": 3171,
     *                     "nama_kabupaten": "Jakarta Pusat",
     *                     "kecamatan_id": 317101,
     *                     "nama_kecamatan": "Menteng",
     *                     "alamat": "Jl. Menteng Raya No. 1",
     *                     "kode_pos": "10310",
     *                     "telepon": "021-1234567",
     *                     "email": "menteng@jakarta.go.id",
     *                     "tahun_pembentukan": "1978",
     *                     "dasar_pembentukan": "Peraturan Pemerintah No. 25 Tahun 1978",
     *                     "file_struktur_organisasi": "/storage/profil/struktur_menteng.pdf",
     *                     "file_logo": "/storage/profil/logo_menteng.png",
     *                     "sambutan": "Selamat datang di website Kecamatan Menteng...",
     *                     "visi": "Terwujudnya Kecamatan Menteng yang maju dan sejahtera",
     *                     "misi": "1. Meningkatkan pelayanan publik\n2. Membangun infrastruktur\n3. Melestarikan budaya lokal",
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
     *                 "self": "http://localhost:8000/api/frontend/v1/profil?sort=nama_kecamatan&page=1",
     *                 "first": "http://localhost:8000/api/frontend/v1/profil?sort=nama_kecamatan&page=1",
     *                 "last": "http://localhost:8000/api/frontend/v1/profil?sort=nama_kecamatan&page=1"
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
            return $this->fractal($this->profilApiRepository->data(), new ProfilTransformer());
        });
    }    
}