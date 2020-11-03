<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\AnggaranDesa;
use App\Models\DataDesa;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
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
use function number_format;
use function redirect;
use function request;
use function route;
use function view;
use function years_list;

class AnggaranDesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'APBDes';
        $page_description = 'Data Anggran Desa';
        return view('data.anggaran_desa.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataAnggaran()
    {
        return DataTables::of(AnggaranDesa::select('*')->get())
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.anggaran-desa.edit', $row->id);
                $delete_url = route('data.anggaran-desa.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })->editColumn('bulan', function ($row) {
                return months_list()[$row->bulan];
            })
            ->editColumn('desa_id', function ($row) {
                return $row->desa->nama;
            })
            ->editColumn('jumlah', function ($row) {
                return number_format($row->jumlah, 2);
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
        $page_description = 'Import Data Anggaran Desa';
        $years_list       = years_list();
        $months_list      = months_list();
        $list_desa        = DataDesa::where('kecamatan_id', config('app.default_profile'))->get();
        return view('data.anggaran_desa.import', compact('page_title', 'page_description', 'years_list', 'months_list', 'list_desa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        ini_set('max_execution_time', 300);
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $desa  = $request->input('desa');

        request()->validate([
            'file' => 'file|mimes:xls,xlsx,csv|max:5120',
        ]);

        if ($request->hasFile('file') && $this->uploadValidation($bulan, $tahun, $desa)) {
            try {
                $path = RequestFacade::file('file')->getRealPath();

                $data = Excel::load($path, function ($reader) {
                })->get();

                if (! empty($data) && $data->count()) {
                    foreach ($data->toArray() as $key => $value) {
                        if (! empty($value)) {
                            $insert[] = [
                                'no_akun'   => $value['no_akun'],
                                'nama_akun' => $value['nama_akun'],
                                'jumlah'    => $value['jumlah'],
                                'bulan'     => $bulan,
                                'tahun'     => $tahun,
                                'desa_id'   => $desa,
                            ];
                        }
                    }

                    if (! empty($insert)) {
                        try {
                            AnggaranDesa::insert($insert);
                            return back()->with('success', 'Import data sukses.');
                        } catch (QueryException $ex) {
                            return back()->with('error', 'Import data gagal. ' . $ex->getCode());
                        }
                    }
                }
            } catch (Exception $ex) {
                return back()->with('error', 'Import data gagal. ' . $ex->getCode());
            }
        } else {
            return back()->with('error', 'Import data gagal. Data sudah pernah diimport.');
        }
    }

    protected function uploadValidation($bulan, $tahun, $desa)
    {
        return ! AnggaranDesa::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('desa_id', $desa)
            ->exists();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $anggaran         = AnggaranDesa::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Anggaran Desa: ' . $anggaran->id;

        return view('data.anggaran_desa.edit', compact('page_title', 'page_description', 'anggaran'));
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
                'bulan'     => 'required',
                'tahun'     => 'required',
                'no_akun'   => 'required',
                'nama_akun' => 'required',
                'jumlah'    => 'required|numeric',
            ]);

            AnggaranDesa::find($id)->update($request->all());

            return redirect()->route('data.anggaran-desa.index')->with('success', 'Data berhasil disimpan!');
        } catch (QueryException $e) {
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
            AnggaranDesa::findOrFail($id)->delete();

            return redirect()->route('data.anggaran-desa.index')->with('success', 'Data sukses dihapus!');
        } catch (QueryException $e) {
            return redirect()->route('data.anggaran-desa.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
