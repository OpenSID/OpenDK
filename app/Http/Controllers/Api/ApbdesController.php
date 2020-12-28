<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApbdesRequest;
use App\Jobs\ApbdesQueueJob;

use Illuminate\Http\Request;
use App\Imports\ImporLaporanApbdes;
use App\Models\Apbdes;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use ZipArchive;

use function back;
use function compact;
use function config;
use function convert_born_date_to_age;
use function redirect;
use function request;
use function route;
use function strtolower;
use function substr;
use function ucwords;
use function view;

class ApbdesController extends Controller
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
     * Hapus Data Apbdes Sesuai OpenSID
     *
     * @param ApbdesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ApbdesRequest $request)
    {
        // dispatch queue job apbdes
        ApbdesQueueJob::dispatch($request->all());

        return response()->json([
            'message' => 'Proses sync data apbdes OpenSID sedang berjalan',
        ]);
    }

    /**
     * Tambah dan Ubah Data dan Foto Apbdes Sesuai OpenSID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storedata(Request $request)
    {
        $this->validate($request, [
            'file' => 'file|mimes:zip|max:51200',
        ]);

        try {
            // Upload file zip temporary.
            $file = $request->file('file');
            $file->storeAs('temp', $name = $file->getClientOriginalName());

            // Temporary path file
            $path = storage_path("app/temp/{$name}");
            $extract = storage_path('app/public/apbdes/');

            // Ekstrak file
            $zip = new ZipArchive;
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            // Proses impor excell
            (new ImporLaporanApbdes())
                ->queue($extract . $excellName = Str::replaceLast('zip', 'xlsx', $name));
        } catch (Exception $e) {
            return back()->with('error', 'Import data gagal. ' . $e->getMessage());
        }

        // Hapus folder temp ketika sudah selesai
        Storage::deleteDirectory('temp');
        // Hapus file excell temp ketika sudah selesai
        Storage::disk('public')->delete('apbdes/' . $excellName);

        return back()->with('success', 'Import data sukses.');
    }
}
