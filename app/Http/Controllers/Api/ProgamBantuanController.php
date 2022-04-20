<?php

namespace App\Http\Controllers\Api;


use ZipArchive;
use Illuminate\Support\Str;
use App\Imports\SinkronBantuan;
use App\Http\Controllers\Controller;
use App\Imports\SinkronPesertaBantuan;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProgramBantuanRequest;


class ProgamBantuanController extends Controller
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

    public function store(ProgramBantuanRequest $request)
    {
        try {
            // Upload file zip temporary.
            $file = $request->file('file');
            $file->storeAs('temp', $name = $file->getClientOriginalName());

            // Temporary path file
            $path = storage_path("app/temp/{$name}");
            $extract = storage_path('app/public/bantuan/');

            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            // Proses impor excell
            (new SinkronBantuan())
                ->queue($extract . $excellName = Str::replaceLast('zip', 'xlsx', $name));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Import data gagal.');
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
        // Hapus file excell temp ketika sudah selesai
        Storage::disk('public')->delete('bantuan' . $excellName);

        return response()->json([
            "message" => "Data Bantuan Telah Berhasil di Sinkronkan",
        ]);
    }

    public function storePeserta(ProgramBantuanRequest $request)
    {
        try {
            // Upload file zip temporary.
            $file = $request->file('file');
            $file->storeAs('temp', $name = $file->getClientOriginalName());

            // Temporary path file
            $path = storage_path("app/temp/{$name}");
            $extract = storage_path('app/public/bantuan/');

            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            // Proses impor excell
            (new SinkronPesertaBantuan())
                ->queue($extract . $excellName = Str::replaceLast('zip', 'xlsx', $name));
        } catch (\Exception $e) {
            return response()->json([
                "status" => "danger",
                "message" => $e->getMessage(),
            ]);
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
        // Hapus file excell temp ketika sudah selesai
        Storage::disk('public')->delete('bantuan/' . $excellName);

        return response()->json([
            "status" => "success",
            "message" => "Data Bantuan Sedang di Sinkronkan",
        ]);
    }

}
