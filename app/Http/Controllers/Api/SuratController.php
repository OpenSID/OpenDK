<?php

namespace App\Http\Controllers\Api;

use App\Models\Surat;
use Illuminate\Http\Request;
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
            'format'  => 'required|string',
            'file'    => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $file           = $request->file('file');
        $original_name  = strtolower(trim($file->getClientOriginalName()));
        $file_name      = time() .  '_' . $original_name;
        Storage::putFileAs('public/surat', $file, $file_name);
        $input['foto']  = $file_name;

        $surat = Surat::create([
            'desa_id'     => $request->desa_id,
            'nik'         => $request->nik,
            'pengurus_id' => $this->nama_camat->id,
            'tanggal'     => $request->tanggal,
            'nomor'       => $request->nomor,
            'nama'        => $request->nama,
            'format'      => $request->format,
            'file'        => $file_name,
        ]);

        return new SuratResource(true, 'Surat Berhasil Dikirim!', $surat);
    }
}
