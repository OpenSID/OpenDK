<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Imunisasi;
use App\Models\Profil;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as RequestFacade;
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

class ImunisasiController extends Controller
{
    public $nama_kecamatan;
    public $bulan;
    public $tahun;

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
        $page_title       = 'Imunisasi';
        $page_description = 'Data Cakupan Imunisasi Kecamatan ' . $this->nama_kecamatan;
        return view('data.imunisasi.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataAKIAKB()
    {
        return DataTables::of(Imunisasi::with(['desa'])->select('*')->get())
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.imunisasi.edit', $row->id);
                $delete_url = route('data.imunisasi.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->editColumn('desa_id', function ($row) {
                return $row->desa->nama;
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
        $page_description = 'Import Data Cakupan Imunisasi';
        $years_list       = years_list();
        $months_list      = months_list();
        return view('data.imunisasi.import', compact('page_title', 'page_description', 'kecamatan_id', 'list_desa', 'years_list', 'months_list'));
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

        request()->validate([
            'file' => 'file|mimes:xls,xlsx,csv|max:5120',
        ]);

        if ($request->hasFile('file') && $this->uploadValidation($bulan, $tahun)) {
            try {
                $path = RequestFacade::file('file')->getRealPath();

                $data = Excel::load($path, function ($reader) {
                })->get();

                if (! empty($data) && $data->count()) {
                    foreach ($data->toArray() as $key => $value) {
                        if (! empty($value)) {
                            $insert[] = [
                                'kecamatan_id'      => config('app.default_profile'),
                                'desa_id'           => $value['desa_id'],
                                'cakupan_imunisasi' => $value['cakupan_imunisasi'],
                                'bulan'             => $bulan,
                                'tahun'             => $tahun,
                            ];
                        }
                    }

                    if (! empty($insert)) {
                        try {
                            Imunisasi::insert($insert);
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

    protected function uploadValidation($bulan, $tahun)
    {
        return ! Imunisasi::where('bulan', $bulan)->where('tahun', $tahun)->exists();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $imunisasi        = Imunisasi::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Cakupan Imunisasi: ' . $imunisasi->id;

        return view('data.imunisasi.edit', compact('page_title', 'page_description', 'imunisasi'));
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
                'cakupan_imunisasi' => 'required',
            ]);

            Imunisasi::find($id)->update($request->all());

            return redirect()->route('data.imunisasi.index')->with('success', 'Data berhasil disimpan!');
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
            Imunisasi::findOrFail($id)->delete();

            return redirect()->route('data.imunisasi.index')->with('success', 'Data sukses dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('data.imunisasi.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
