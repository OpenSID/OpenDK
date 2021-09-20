<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaporanApbdesRequest;
use App\Jobs\LaporanApbdesQueueJob;

use function response;

class LaporanApbdesController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Tambah / Ubah Data Apbdes Sesuai OpenSID
     *
     * @param ApbdesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LaporanApbdesRequest $request)
    {
        // dispatch queue job apbdes
        LaporanApbdesQueueJob::dispatch($request->only(['desa_id', 'laporan_apbdes']));

        return response()->json([
            'status' => 'success',
            'message' => 'Proses sync data APBDes OpenSID sedang berjalan',
        ]);
    }
}
