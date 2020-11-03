<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\LogImport;
use App\Models\TingkatPendidikan;
use App\Models\Wilayah;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function config;
use function ini_set;
use function months_list;
use function redirect;
use function request;
use function route;
use function view;
use function years_list;

class TingkatPendidikanController extends Controller
{
    public function index()
    {
        $kecamatan        = Wilayah::where('kode', config('app.default_profile'))->first();
        $page_title       = 'Tingkat Pendidikan';
        $page_description = 'Data Tingkat Pendidikan Kecamatan ' . $kecamatan->nama_kecamatan;
        return view('data.tingkat_pendidikan.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataTingkatPendidikan()
    {
        return DataTables::of(TingkatPendidikan::with(['desa'])->select('*')->get())
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.tingkat-pendidikan.edit', $row->id);
                $delete_url = route('data.tingkat-pendidikan.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->editColumn('desa_id', function ($row) {
                return $row->desa->nama;
            })
            ->rawColumns(['actions'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Import';
        $page_description = 'Import Data Tingkat Pendidikan';
        $years_list       = years_list();
        $months_list      = months_list();
        return view('data.tingkat_pendidikan.import', compact('page_title', 'page_description', 'list_desa', 'years_list', 'months_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        ini_set('max_execution_time', 300);
        $semester = $request->input('semester');
        $tahun    = $request->input('tahun');
        $desa_id  = $request->input('desa_id');

        $tmp = $desa_id;

        $validator = Validator::make($request->all(), [
            'semester' => 'required',
            'tahun'    => 'required',
            'desa_id'  => 'required',
            'file'     => 'file|mimes:xls,xlsx,csv|max:5120',
        ]);

        if ($validator->fails() && $this->uploadValidation($desa_id, $semester, $tahun)) {
            try {
                $path = RequestFacade::file('file')->getRealPath();

                $data = Excel::load($path, function ($reader) {
                })->get();

                if (! empty($data) && $data->count()) {
                    $import = LogImport::create([
                        'nama_tabel' => 'das_tingkat_pendidikan',
                        'desa_id'    => $desa_id,
                        'bulan'      => $semester,
                        'tahun'      => $tahun,
                    ]);

                    foreach ($data->toArray() as $key => $value) {
                        if (! empty($value)) {
                            foreach ($value as $v) {
                                $insert[] = [
                                    'kecamatan_id'            => config('app.default_profile'),
                                    'desa_id'                 => $desa_id,
                                    'tidak_tamat_sekolah'     => $v['tidak_tamat_sekolah'] ?? 0,
                                    'tamat_sd'                => $v['tamat_sd_sederajat'] ?? 0,
                                    'tamat_smp'               => $v['tamat_smp_sederajat'] ?? 0,
                                    'tamat_sma'               => $v['tamat_sma_sederajat'] ?? 0,
                                    'tamat_diploma_sederajat' => $v['tamat_diploma_sederajat'] ?? 0,
                                    'semester'                => $semester,
                                    'tahun'                   => $tahun,
                                    'import_id'               => $import->id,
                                ];
                            }
                        }
                    }

                    if (! empty($insert)) {
                        try {
                            TingkatPendidikan::insert($insert);
                            return back()->with('success', 'Import data sukses.');
                        } catch (QueryException $ex) {
                            return back()->with('error', 'Import data gagal. ' . $ex->getMessage());
                        }
                    }
                }
            } catch (Exception $ex) {
                return back()->with('error', 'Import data gagal. ' . $ex->getMessage() . $tmp);
            }
        } else {
            return back()->with('error', 'Import data gagal. Data sudah pernah diimport.');
        }
    }

    protected function uploadValidation($desa_id, $semester, $tahun)
    {
        return ! TingkatPendidikan::where('semester', $semester)->where('tahun', $tahun)->where('desa_id', $desa_id)->exists();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $pendidikan       = TingkatPendidikan::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Tingkat Pendidikan';
        return view('data.tingkat_pendidikan.edit', compact('page_title', 'page_description', 'pendidikan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            request()->validate([
                'tidak_tamat_sekolah'     => 'required',
                'tamat_sd'                => 'required',
                'tamat_smp'               => 'required',
                'tamat_sma'               => 'required',
                'tamat_diploma_sederajat' => 'required',
                'bulan'                   => 'required',
                'tahun'                   => 'required',
            ]);

            TingkatPendidikan::find($id)->update($request->all());

            return redirect()->route('data.tingkat-pendidikan.index')->with('success', 'Data berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            TingkatPendidikan::findOrFail($id)->delete();

            return redirect()->route('data.tingkat-pendidikan.index')->with('success', 'Data sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('data.tingkat-pendidikan.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
