<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\EpidemiPenyakit;
use App\Models\JenisPenyakit;
use App\Models\Profil;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as RequestFacade;
use Yajra\DataTables\Facades\DataTables;

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

class EpidemiPenyakitController extends Controller
{
    public $nama_kecamatan;

    public function __construct()
    {
        $this->nama_kecamatan = Profil::where('kecamatan_id', config('app.default_profile'))->first()->kecamatan->nama;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Epidemi Penyakit';
        $page_description = 'Data Epidemi Penyakit Kecamatan ' . $this->nama_kecamatan;
        return view('data.epidemi_penyakit.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataAKIAKB()
    {
        return DataTables::of(EpidemiPenyakit::with(['penyakit'])->select('*')->get())
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.epidemi-penyakit.edit', $row->id);
                $delete_url = route('data.epidemi-penyakit.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->editColumn('penyakit_id', function ($row) {
                return $row->penyakit->nama;
            })
            ->editColumn('bulan', function ($row) {
                return months_list()[$row->bulan];
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
        $page_description = 'Import Data Epidemi Penyakit';
        $years_list       = years_list();
        $months_list      = months_list();
        $jenis_penyakit   = JenisPenyakit::pluck('nama', 'id');
        return view('data.epidemi_penyakit.import', compact('page_title', 'page_description', 'kecamatan_id', 'list_desa', 'years_list', 'months_list', 'jenis_penyakit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        ini_set('max_execution_time', 300);
        $bulan       = $request->input('bulan');
        $tahun       = $request->input('tahun');
        $penyakit_id = $request->input('penyakit_id');

        request()->validate([
            'file' => 'file|mimes:xls,xlsx,csv|max:5120',
        ]);

        if ($request->hasFile('file') && $this->uploadValidation($bulan, $tahun, $penyakit_id)) {
            try {
                $path = RequestFacade::file('file')->getRealPath();

                $data = Excel::load($path, function ($reader) {
                })->get();

                if (! empty($data) && $data->count()) {
                    foreach ($data->toArray() as $key => $value) {
                        if (! empty($value)) {
                            foreach ($value as $v) {
                                $insert[] = [
                                    'kecamatan_id'     => config('app.default_profile'),
                                    'desa_id'          => $v['desa_id'],
                                    'jumlah_penderita' => $v['jumlah_penderita'],
                                    'bulan'            => $bulan,
                                    'tahun'            => $tahun,
                                    'penyakit_id'      => $penyakit_id,
                                ];
                            }
                        }
                    }

                    if (! empty($insert)) {
                        try {
                            EpidemiPenyakit::insert($insert);
                            return back()->with('success', 'Import data sukses.');
                        } catch (QueryException $ex) {
                            return back()->with('error', 'Import data gagal. ' . $ex->getCode());
                        }
                    }
                }
            } catch (\Exception $ex) {
                return back()->with('error', 'Import data gagal. ' . $ex->getMessage());
            }
        } else {
            return back()->with('error', 'Import data gagal. Data sudah pernah diimport.');
        }
    }

    protected function uploadValidation($bulan, $tahun, $penyakit_id)
    {
        return ! EpidemiPenyakit::where('bulan', $bulan)->where('tahun', $tahun)->where('penyakit_id', $penyakit_id)->exists();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $epidemi          = EpidemiPenyakit::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Epidemi Penyakit: ' . $epidemi->penyakit->nama;
        $jenis_penyakit   = JenisPenyakit::pluck('nama', 'id');
        return view('data.epidemi_penyakit.edit', compact('page_title', 'page_description', 'epidemi', 'jenis_penyakit'));
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
                'jumlah_penderita' => 'required',
                'penyakit_id'      => 'required',
                'bulan'            => 'required',
                'tahun'            => 'required',
            ]);

            EpidemiPenyakit::find($id)->update($request->all());

            return redirect()->route('data.epidemi-penyakit.index')->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
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
            EpidemiPenyakit::findOrFail($id)->delete();

            return redirect()->route('data.epidemi-penyakit.index')->with('success', 'Data sukses dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('data.epidemi-penyakit.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
