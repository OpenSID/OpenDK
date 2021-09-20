<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporLaporanApbdes;
use App\Models\DataDesa;
use App\Models\LaporanApbdes;
use function back;
use function compact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function redirect;
use function route;
use function view;
use Yajra\DataTables\DataTables;
use ZipArchive;

class LaporanApbdesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(LaporanApbdes $apbdes)
    {
        $page_title       = 'Laporan APBDes';
        $page_description = 'Data APBDes';
        $list_desa        = DataDesa::get();

        return view('data.laporan-apbdes.index', compact('page_title', 'page_description', 'list_desa'));
    }

    /**
     * Return datatable Data Apbdes.
     *
     * @param Request $request
     * @return DataTables
     */
    public function getApbdes(Request $request)
    {
        $desa = $request->input('desa');

        $query = DB::table('das_apbdes')
            ->leftJoin('das_data_desa', 'das_apbdes.desa_id', '=', 'das_data_desa.desa_id')
            ->select([
                'das_apbdes.id',
                'das_data_desa.nama as nama_desa',
                'das_apbdes.judul',
                'das_apbdes.tahun',
                'das_apbdes.semester',
                'das_apbdes.nama_file',
                'das_apbdes.imported_at',
            ])
            ->when($desa, function ($query) use ($desa) {
                return $desa === 'ALL'
                    ? $query
                    : $query->where('das_data_desa.desa_id', $desa);
            });

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $delete_url = route('data.laporan-apbdes.destroy', $row->id);
                $download_url = asset('storage/apbdes/' . $row->nama_file);

                $data['delete_url'] = $delete_url;
                $data['download_url'] = $download_url;

                return view('forms.action', $data);
            })->make();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $apbdes = LaporanApbdes::findOrFail($id);

            // Hapus file apbdes
            Storage::disk('public')->delete('apbdes/' . $apbdes->nama_file);

            $apbdes->delete();

            return redirect()->route('data.laporan-apbdes.index')->with('success', 'Data sukses dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('data.laporan-apbdes.index')->with('error', 'Data gagal dihapus!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Laporan APBDes';
        $page_description = 'Import Data';

        return view('data.laporan-apbdes.import', compact('page_title', 'page_description'));
    }

    /**
     * Impor data apbdes dari file Excel.
     * Kalau apbdes sudah ada (berdasarkan NIK), update dengan data yg diimpor
     *
     * @return Response
     */
    public function do_import(Request $request)
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
            $extract = storage_path('app/temp/apbdes/');

            // Ekstrak file
            $zip = new ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            // Proses impor excell
            (new ImporLaporanApbdes())
                ->queue($extract . Str::replaceLast('zip', 'xlsx', $name));
        } catch (Exception $e) {
            return back()->with('error', 'Import data gagal. ' . $e->getMessage());
        }

        return back()->with('success', 'Import data sukses.');
    }
}
