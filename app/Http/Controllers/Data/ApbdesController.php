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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Apbdes $apbdes)
    {
        $page_title       = 'Dokumentasi Laporan Apbdes';
        $page_description = '';
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
                'das_apbdes.nama',
                'das_apbdes.tahun',
                'das_apbdes.semester',
                'das_apbdes.tgl_upload',
                'das_apbdes.nama_file',
                'das_apbdes.mime_type',
                'das_data_desa.nama as nama_desa',
            ])
            ->when($desa, function ($query) use ($desa) {
                return $desa === 'ALL'
                    ? $query
                    : $query->where('das_data_desa.desa_id', $desa);
            });

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $delete_url = route('data.apbdes.destroy', $row->id);
                $show_url   = route('data.apbdes.show', $row->id);

                $data['delete_url'] = $delete_url;
                $data['show_url'] = $show_url;

                return view('forms.action', $data);
              })
              ->editColumn('nama', function ($row) {
                  return $row->nama;
              })->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Apbdes $apbdes)
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Data Apbdes';

        return view('data.apbdes.create', compact('page_title', 'page_description'));
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
                'nama_file'            => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
                'mime_type'            => 'required',
            ]);

            if ($request->hasFile('nama_file')) {
                $file     = $request->file('nama_file');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/apbdes/";
                $request->file('nama_file')->move($path, $fileName);
                $apbdes->nama_file = $path . $fileName;
                $apbdes->mime_type = $file->getClientOriginalExtension();
            }

            $apbdes->save();
            return redirect()->route('data.apbdes.index')->with('success', 'Apbdes berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Apbdes gagal disimpan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $apbdes   = Apbdes::find($id);
        $page_title = $apbdes->nama;

        return view('data.apbdes.show', compact('page_title', 'apbdes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $apbdes         = Apbdes::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Apbdes : ' . $apbdes->nama;

        return view('data.apbdes.edit', compact('page_title', 'page_description', 'apbdes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function download($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // Save Request
        try {
            $apbdes = Apbdes::where('id', $id)->first();
            $apbdes->fill($request->all());

            request()->validate([
              'nama'                 => 'required',
              'tahun'                => 'required',
              'semester'             => 'required',
              'tgl_upload'           => 'required',
              'nama_file'            => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
              'mime_type'            => 'required',
            ]);

            if ($request->hasFile('nama_file')) {
                $file     = $request->file('nama_file');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/apbdes/";
                $request->file('nama_file')->move($path, $fileName);
                $apbdes->nama_file = $path . $fileName;
                $apbdes->mime_type = $file->getClientOriginalExtension();
            }

            $apbdes->update();

            return redirect()->route('data.apbdes.index')->with('success', 'Apbdes berhasil disimpan!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Apbdes gagal disimpan!');
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
            Apbdes::findOrFail($id)->delete();

            return redirect()->route('data.apbdes.index')->with('success', 'Apbdes sukses dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('data.apbdes.index')->with('error', 'Apbdes gagal dihapus!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Import';
        $page_description = 'Import Data Apbdes';

        $list_desa = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', config('app.default_profile'))->get();
        return view('data.apbdes.import', compact('page_title', 'page_description', 'list_desa'));
    }

    /**
     * Impor data apbdes dari file Excel.
     * Kalau apbdes sudah ada (berdasarkan NIK), update dengan data yg diimpor
     *
     * @return Response
     */
    public function importExcel(Request $request)
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
            (new ImporLaporanApbdes($request->all()))
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
