<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporLaporanPenduduk;
use App\Models\DataDesa;
use App\Models\LaporanPenduduk;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

use Exception;
use ZipArchive;

use function back;
use function compact;
use function redirect;
use function route;
use function view;

class LaporanPendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(LaporanPenduduk $penduduk)
    {
        $page_title       = 'Laporan Penduduk';
        $page_description = 'Data Penduduk';
        $list_desa        = DataDesa::get();

        return view('data.laporan-penduduk.index', compact('page_title', 'page_description', 'list_desa'));
    }

    /**
     * Return datatable Data Laporan Penduduk.
     *
     * @param Request $request
     * @return DataTables
     */
    public function getData(Request $request)
    {
        $desa = $request->input('desa');

        $query = DB::table('das_laporan_penduduk')
            ->leftJoin('das_data_desa', 'das_laporan_penduduk.desa_id', '=', 'das_data_desa.desa_id')
            ->select([
                'das_laporan_penduduk.id',
                'das_data_desa.nama as nama_desa',
                'das_laporan_penduduk.judul',
                'das_laporan_penduduk.bulan',
                'das_laporan_penduduk.tahun',
                'das_laporan_penduduk.nama_file',
                'das_laporan_penduduk.imported_at',
            ])
            ->when($desa, function ($query) use ($desa) {
                return $desa === 'ALL'
                    ? $query
                    : $query->where('das_data_desa.desa_id', $desa);
            });

        return DataTables::of($query)
            ->addColumn('action', function ($row) {

                $delete_url = route('data.laporan-penduduk.destroy', $row->id);
                $download_url = asset('storage/laporan_penduduk/' . $row->nama_file);

                return view('forms.action', compact('delete_url', 'download_url'));
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
            $penduduk = LaporanPenduduk::findOrFail($id);
            
            // Hapus file penduduk
            Storage::disk('public')->delete('laporan_penduduk/' . $penduduk->nama_file);

            $penduduk->delete();

            return redirect()->route('data.laporan-penduduk.index')->with('success', 'Data sukses dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('data.laporan-penduduk.index')->with('error', 'Data gagal dihapus!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function import()
    {  
        $page_title       = 'Laporan Penduduk';
        $page_description = 'Import Data';
        
        return view('data.laporan-penduduk.import', compact('page_title', 'page_description'));
    }

    /**
     * Impor data penduduk dari file Excel.
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
            $extract = storage_path('app/temp/laporan_penduduk/');

            // Ekstrak file
            $zip = new ZipArchive;
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            // Proses impor excell
            (new ImporLaporanPenduduk())
                ->queue($extract . Str::replaceLast('zip', 'xlsx', $name));
        } catch (Exception $e) {
            return back()->with('error', 'Import data gagal. ' . $e->getMessage());
        }

        return redirect()->route('data.laporan-penduduk.index')->with('success', 'Import data sukses');
    }

}