<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\LaporanPendudukQueueJob;
use App\Http\Requests\LaporanPendudukRequest;

class LaporanPendudukController extends Controller
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
     * Tambah / Ubah Data Laporan Penduduk Dari OpenSID
     *
     * @param LaporanPendudukRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LaporanPendudukRequest $request)
    {
        LaporanPendudukQueueJob::dispatch($request->only(['desa_id', 'laporan_penduduk']));

        return response()->json([
            'status' => 'success',
            'message' => 'Proses sync data Laporan Penduduk OpenSID sedang berjalan'
        ]);
    }
}