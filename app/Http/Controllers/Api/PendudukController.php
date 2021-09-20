<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PendudukRequest;
use App\Imports\SinkronPenduduk;

use App\Jobs\PendudukQueueJob;

use App\Models\Penduduk;
use function back;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function response;

use ZipArchive;

class PendudukController extends Controller
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
     * Hapus Data Penduduk Sesuai OpenSID
     *
     * @param PendudukRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PendudukRequest $request)
    {
        // dispatch queue job penduduk
        PendudukQueueJob::dispatch($request->all());

        return response()->json([
          'status' => 'success',
          'message' => 'Proses sync Data Penduduk OpenSID sedang berjalan',
        ]);
    }

    /**
     * Tambah dan Ubah Data dan Foto Penduduk Sesuai OpenSID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storedata(Request $request)
    {
        $this->validate($request, [
        'file' => 'file|mimes:zip|max:5120',
      ]);

        try {
            // Upload file zip temporary.
            $file = $request->file('file');
            $file->storeAs('temp', $name = $file->getClientOriginalName());

            // Temporary path file
            $path = storage_path("app/temp/{$name}");
            $extract = storage_path('app/public/penduduk/foto/');

            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            // Proses impor excell
            (new SinkronPenduduk())
          ->queue($extract . $excellName = Str::replaceLast('zip', 'xlsx', $name));
        } catch (Exception $e) {
            return back()->with('error', 'Import data gagal. ' . $e->getMessage());
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
        // Hapus file excell temp ketika sudah selesai
        Storage::disk('public')->delete('penduduk/foto/' . $excellName);

        return response()->json([
        "message" => "Data Foto Telah Berhasil di Sinkronkan",
      ]);
    }
}
