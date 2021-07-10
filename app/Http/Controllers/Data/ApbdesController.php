<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporLaporanApbdes;
use App\Models\DataDesa;
use App\Models\Apbdes;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use ZipArchive;

use function back;
use function compact;
use function config;
use function redirect;
use function request;
use function route;
use function substr;
use function view;

class ApbdesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Apbdes $apbdes)
    {
        $page_title       = 'Laporan APBDes';
        $page_description = 'Data APBDes';
        $list_desa        = DataDesa::get();

        return view('data.apbdes.index', compact('page_title', 'page_description', 'list_desa'));
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
                $delete_url = route('data.apbdes.destroy', $row->id);
                $download_url = asset('storage/apbdes/' . $row->nama_file);

                $data['delete_url'] = $delete_url;
                $data['download_url'] = $download_url;

                return view('forms.action', $data);
              })->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Save Request
        try {
            $apbdes                = new Apbdes($request->all());
            $apbdes->kecamatan_id  = config('app.default_profile');
            $apbdes->provinsi_id   = substr($apbdes->kecamatan_id, 0, 2);
            $apbdes->kabupaten_id  = substr($apbdes->kecamatan_id, 0, 5);

            request()->validate([
                'nama'                 => 'required',
                'tahun'                => 'required',
                'semester'             => 'required',
                'tgl_upload'           => 'required',
                'nama_file'            => 'required|file|mimes:pdf|max:2048',
            ]);

            if ($request->hasFile('nama_file')) {
                $file     = $request->file('nama_file');
                $fileName = $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension() ;
                $path     = "storage/apbdes/";
                $request->file('nama_file')->move($path, $fileName);
                $apbdes->nama_file = $path . $fileName;
            }

            $apbdes->save();
            return redirect()->route('data.apbdes.index')->with('success', 'Data berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $apbdes = Apbdes::findOrFail($id);
            
            // Hapus file apbdes
            Storage::disk('public')->delete('apbdes/' . $apbdes->nama_file);

            $apbdes->delete();

            return redirect()->route('data.apbdes.index')->with('success', 'Data sukses dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('data.apbdes.index')->with('error', 'Data gagal dihapus!');
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
        
        return view('data.apbdes.import', compact('page_title', 'page_description'));
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