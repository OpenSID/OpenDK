<?php

namespace App\Http\Controllers\Api;

use App\Models\Surat;
use App\Models\DataDesa;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\SuratResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SuratController extends Controller
{
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'desa_id' => 'required',
            'nik'     => 'required|integer|digits:16',
            'tanggal' => 'required|date',
            'nik'     => 'required|integer|digits:16',
            'nomor'   => 'required|integer',
            'nama'    => 'required|string',
            'file'    => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! in_array($request->desa_id, Arr::flatten(DataDesa::pluck('desa_id')))) {
            Log::debug('Kode desa' . $request->desa_id . 'tidak terdaftar di kecamatan');
            return response()->json('Kode desa ' . $request->desa_id . ' tidak terdaftar di kecamatan', 400);
        }

        $file           = $request->file('file');
        $original_name  = strtolower(trim($file->getClientOriginalName()));
        $file_name      = time() .  '_' . $original_name;
        Storage::putFileAs('public/surat', $file, $file_name);

        $surat = Surat::create([
            'desa_id'     => $request->desa_id,
            'nik'         => $request->nik,
            'pengurus_id' => $this->nama_camat->id,
            'tanggal'     => $request->tanggal,
            'nomor'       => $request->nomor,
            'nama'        => $request->nama,
            'file'        => $file_name,
        ]);

        return new SuratResource(true, 'Surat Berhasil Dikirim!', $surat);
    }
}
