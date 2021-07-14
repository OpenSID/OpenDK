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
        // LaporanApbdesQueueJob::dispatch($request->only(['judul', 'tahun', 'semester', 'nama_file', 'file', 'desa_id']));
        LaporanApbdesQueueJob::dispatch($request->all());

        return response()->json([
            'message' => 'Proses sync data apbdes OpenSID sedang berjalan',
        ]);
    }
}