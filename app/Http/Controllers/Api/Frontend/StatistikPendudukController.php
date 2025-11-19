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

use App\Repositories\StatistikPendudukApiRepository;
use App\Transformers\StatistikPendudukTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Spatie\Fractal\Fractal;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="OpenDK Desa API",
 *     description="API untuk mengakses data profil dengan Spatie Query Builder filtering dan sorting",
 *     @OA\Contact(
 *         name="OpenDK Development Team",
 *         email="dev@opendesa.id"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Desa",
 *     description="API endpoints untuk mengelola profil"
 * )
 */

class StatistikPendudukController extends BaseController
{
    protected StatistikPendudukApiRepository $repository;

    public function __construct(
        StatistikPendudukApiRepository $repository
    ) {
        $this->repository = $repository;
        $this->prefix = config('theme-api.statistikPenduduk.cache_prefix', 'statistikPenduduk:api');
    }

    /**
     * Display statistik penduduk with dashboard and chart data.
     *
     * @OA\Get(
     *     path="/api/v1/frontend/statistik-penduduk",
     *     summary="Get statistik penduduk",
     *     description="Retrieve statistik penduduk data with dashboard metrics and various charts",
     *     tags={"Statistik Penduduk"},
     *     @OA\Parameter(
     *         name="filter[kategori]",
     *         in="query",
     *         description="Category for filtering",
     *         required=false,
     *         @OA\Schema(type="string", default="Semua")
     *     ),
     *     @OA\Parameter(
     *         name="filter[tahun]",
     *         in="query",
     *         description="Year for filtering",
     *         required=false,
     *         @OA\Schema(type="string", default="2025")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="type", type="string", example="statistik-penduduk"),
     *                 @OA\Property(property="id", type="string", example="1"),
     *                 @OA\Property(property="attributes", type="object",
     *                     @OA\Property(property="dashboard", type="object",
     *                         @OA\Property(property="total_penduduk", type="integer", example=96),
     *                         @OA\Property(property="total_lakilaki", type="integer", example=45),
     *                         @OA\Property(property="total_perempuan", type="integer", example=51),
     *                         @OA\Property(property="total_disabilitas", type="integer", example=0),
     *                         @OA\Property(property="ktp_wajib", type="integer", example=88),
     *                         @OA\Property(property="ktp_terpenuhi", type="integer", example=0),
     *                         @OA\Property(property="ktp_persen_terpenuhi", type="string", example="0,00"),
     *                         @OA\Property(property="akta_terpenuhi", type="integer", example=0),
     *                         @OA\Property(property="akta_persen_terpenuhi", type="string", example="0,00"),
     *                         @OA\Property(property="aktanikah_wajib", type="integer", example=50),
     *                         @OA\Property(property="aktanikah_terpenuhi", type="integer", example=0),
     *                         @OA\Property(property="aktanikah_persen_terpenuhi", type="string", example="0,00")
     *                     ),
     *                     @OA\Property(property="chart", type="object",
     *                         @OA\Property(property="penduduk", type="array", @OA\Items(
     *                             @OA\Property(property="year", type="integer", example=2019),
     *                             @OA\Property(property="value_lk", type="integer", example=45),
     *                             @OA\Property(property="value_pr", type="integer", example=51)
     *                         )),
     *                         @OA\Property(property="penduduk-usia", type="array", @OA\Items(
     *                             @OA\Property(property="umur", type="string", example="Bayi (0 - 5 tahun)"),
     *                             @OA\Property(property="value", type="integer", example=0),
     *                             @OA\Property(property="color", type="string", example="#09ffdc")
     *                         )),
     *                         @OA\Property(property="penduduk-pendidikan", type="array", @OA\Items(
     *                             @OA\Property(property="year", type="string", example="2025"),
     *                             @OA\Property(property="SD", type="integer", example=15),
     *                             @OA\Property(property="SLTP", type="integer", example=26),
     *                             @OA\Property(property="SLTA", type="integer", example=28),
     *                             @OA\Property(property="DIPLOMA", type="integer", example=1),
     *                             @OA\Property(property="SARJANA", type="integer", example=0)
     *                         )),
     *                         @OA\Property(property="penduduk-golongan-darah", type="array", @OA\Items(
     *                             @OA\Property(property="blod_type", type="string", example="A"),
     *                             @OA\Property(property="total", type="integer", example=2),
     *                             @OA\Property(property="color", type="string", example="#f97d7d")
     *                         )),
     *                         @OA\Property(property="penduduk-kawin", type="array", @OA\Items(
     *                             @OA\Property(property="status", type="string", example="Belum kawin"),
     *                             @OA\Property(property="total", type="integer", example=40),
     *                             @OA\Property(property="color", type="string", example="#d365f8")
     *                         )),
     *                         @OA\Property(property="penduduk-agama", type="array", @OA\Items(
     *                             @OA\Property(property="religion", type="string", example="Islam"),
     *                             @OA\Property(property="total", type="integer", example=89),
     *                             @OA\Property(property="color", type="string", example="#dcaf1e")
     *                         ))
     *                     )
     *                 )
     *             )
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
        $params = $request->only(['page', 'filter', 'search', 'sort', 'order', 'include','desa','tahun']);
        $cacheKey = $this->getCacheKey('index', $params);        
        return Cache::remember($cacheKey, $this->getCacheDuration(), function () use ($request) {
            $desa = $request->get('desa', 'Semua');
            $tahun = $request->get('tahun', date('Y'));            
            return $this->fractal($this->repository->data($desa, $tahun), new StatistikPendudukTransformer(), 'statistik-penduduk');    
        });
    }
    
    public function listYear(Request $request): Fractal|JsonResponse
    {
        $params = $request->only(['page', 'filter', 'search', 'sort', 'order', 'include']);
        $cacheKey = $this->getCacheKey('listYear', $params);
        return Cache::remember($cacheKey, $this->getCacheDuration(), function () use ($request) {
            return response()->json([                
                "data" => [
                    [
                        "type" => "tahun",                        
                        "attributes" => [
                            $this->repository->yearsList()
                        ]
                    ]                    
                ]
            ]);
        });
    }
}