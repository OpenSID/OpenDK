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

use App\Repositories\KomplainApiRepository;
use App\Http\Requests\Api\Frontend\StoreKomplainRequest;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\PendudukService;
use Illuminate\Support\Facades\Cache;
use App\Transformers\KomplainTransformer;
use Spatie\Fractal\Fractal;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="OpenDK Komplain API",
 *     description="API untuk mengelola data komplain dan jawaban komplain",
 *     @OA\Contact(
 *         name="OpenDK Development Team",
 *         email="dev@opendesa.id"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Komplain",
 *     description="API endpoints untuk mengelola komplain"
 * )
 */

class KomplainController extends BaseController
{
    protected KomplainApiRepository $komplainApiRepository;

    public function __construct(
        KomplainApiRepository $komplainApiRepository
    ) {
        $this->komplainApiRepository = $komplainApiRepository;
        $this->prefix = config('theme-api.komplain.cache_prefix', 'komplain:api');
    }

    /**
     * Display a listing of complaints.
     *
     * @OA\Get(
     *     path="/api/v1/frontend/komplain",
     *     summary="Get list of complaints",
     *     description="Retrieve paginated list of complaints with filtering and search capabilities",
     *     tags={"Komplain"},
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
     *         name="filter[status]",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"REVIEW", "DITERIMA", "DITOLAK", "Selesai"}, example="DITERIMA")
     *     ),
     *     @OA\Parameter(
     *         name="filter[kategori]",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in title and content fields",
     *         required=false,
     *         @OA\Schema(type="string", example="keluhan jalan")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort field",
     *         required=false,
     *         @OA\Schema(type="string", enum={"created_at", "updated_at", "judul"}, default="created_at")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Sort order",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="komplain_id", type="string", example="123456"),
     *                 @OA\Property(property="judul", type="string", example="Jalan Rusak"),
     *                 @OA\Property(property="slug", type="string", example="jalan-rusak-123456"),
     *                 @OA\Property(property="laporan", type="string", example="Jalan di depan rumah saya rusak parah"),
     *                 @OA\Property(property="status", type="string", example="REVIEW"),
     *                 @OA\Property(property="created_at", type="string", example="2025-01-10T09:50:00.000000Z")
     *             )),
     *             @OA\Property(property="meta", type="object", example={
     *                 "pagination": {
     *                     "total": 20,
     *                     "count": 15,
     *                     "per_page": 15,
     *                     "current_page": 1,
     *                     "total_pages": 2
     *                 }
     *             })
     *         )
     *     )
     * )
     */
    public function index(Request $request): Fractal
    {
        $params = $request->only(['page', 'per_page', 'filter', 'fields', 'search', 'sort', 'order', 'include']);
        $cacheKey = $this->getCacheKey('index', $params);

        return Cache::remember($cacheKey, $this->getCacheDuration(), function () use ($request) {
            return $this->fractal($this->komplainApiRepository->data(), new KomplainTransformer(), 'komplain');
        });
    }

    /**
     * Store a newly created complaint.
     *
     * @OA\Post(
     *     path="/api/v1/frontend/komplain",
     *     summary="Create a new complaint",
     *     description="Store a new complaint with attachments",
     *     tags={"Komplain"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nik", "judul", "kategori", "laporan", "tanggal_lahir"},
     *             @OA\Property(property="nik", type="string", example="1234567890123456"),
     *             @OA\Property(property="judul", type="string", example="Jalan Rusak"),
     *             @OA\Property(property="kategori", type="integer", example=1),
     *             @OA\Property(property="laporan", type="string", example="Jalan di depan rumah saya rusak parah"),
     *             @OA\Property(property="tanggal_lahir", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="anonim", type="boolean", example=false),
     *             @OA\Property(property="lampiran1", type="string", format="binary", description="File attachment 1"),
     *             @OA\Property(property="lampiran2", type="string", format="binary", description="File attachment 2"),
     *             @OA\Property(property="lampiran3", type="string", format="binary", description="File attachment 3"),
     *             @OA\Property(property="lampiran4", type="string", format="binary", description="File attachment 4")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Complaint created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", example={
     *                 "id": 1,
     *                 "komplain_id": "123456",
     *                 "judul": "Jalan Rusak",
     *                 "slug": "jalan-rusak-123456",
     *                 "status": "REVIEW",
     *                 "created_at": "2025-01-10T09:50:00.000000Z"
     *             }),
     *             @OA\Property(property="message", type="string", example="Komplain berhasil dikirim. Tunggu Admin untuk di review terlebih dahulu!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={
     *                 "nik": ["The nik field is required."],
     *                 "judul": ["The judul field is required."]
     *             })
     *         )
     *     )
     * )
     */
    public function store(StoreKomplainRequest $request): JsonResponse
    {
        try {
            $komplain = $this->komplainApiRepository->create($request->all());

            $penduduk = $this->isDatabaseGabungan()
                ? (new PendudukService)->cekPendudukNikTanggalLahir($request->input('nik'), $request->input('tanggal_lahir'))
                : Penduduk::where('nik', $komplain->nik)->first();
            if (!$penduduk) {
                return response()->json([
                    'errors' => [
                        'message' => 'Penduduk dengan nik '.$komplain->nik.' tidak ditemukan'
                    ]
                ], 422);
            }
            $komplain->nama = $penduduk['nama'] ?? null;

            // memasukkan data dari api database gabungan ke detail_penduduk
            $komplain->detail_penduduk = $penduduk ? json_encode($penduduk->attributesToArray()) : null;

            // Handle attachments
            $attachments = [];
            for ($i = 1; $i <= 4; $i++) {
                $attachmentKey = 'lampiran' . $i;
                if ($request->hasFile($attachmentKey)) {
                    $attachments[$attachmentKey] = $request->file($attachmentKey);
                }
            }

            $this->komplainApiRepository->saveWithAttachments($komplain, $attachments);

            // Clear cache
            $this->removeCachePrefix();

            return response()->json([
                'data' => $komplain,
                'message' => 'Komplain berhasil dikirim. Tunggu Admin untuk di review terlebih dahulu!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'message' => 'Komplain gagal dikirim!'
                ]
            ], 500);
        }
    }
}
