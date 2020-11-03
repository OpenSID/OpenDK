<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\PutusSekolah;
use App\Models\Wilayah;
use Excel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
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

class PutusSekolahController extends Controller
{
    public function index()
    {
        $kecamatan        = Wilayah::where('kode', config('app.default_profile'))->first();
        $page_title       = 'Anak Putus Sekolah';
        $page_description = 'Data Anak Putus Sekolah Kecamatan ' . $kecamatan->nama_kecamatan;
        return view('data.putus_sekolah.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataPutusSekolah()
    {
        return DataTables::of(PutusSekolah::with(['desa'])->select('*')->get())
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.putus-sekolah.edit', $row->id);
                $delete_url = route('data.putus-sekolah.destroy', $row->id);

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
        $page_description = 'Import Data Anak Putus Sekolah';
        $years_list       = years_list();
        $months_list      = months_list();
        return view('data.putus_sekolah.import', compact('page_title', 'page_description', 'list_desa', 'years_list', 'months_list'));
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

        request()->validate([
            'file' => 'file|mimes:xls,xlsx,csv|max:5120',
        ]);

        if ($request->hasFile('file') && $this->uploadValidation($desa_id, $semester, $tahun)) {
            try {
                $path = Input::file('file')->getRealPath();
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                })->get();
                /*$data = Excel::load($path, function ($reader) {
                })->get();*/

                if (! empty($data) && $data->count()) {
                    foreach ($data as $key => $v) {
                        if (! empty($v)) {
                                $insert[] = [
                                    'kecamatan_id'   => config('app.default_profile'),
                                    'desa_id'        => $desa_id,
                                    'siswa_paud'     => $v['siswa_paud_ra'] ?? 0,
                                    'anak_usia_paud' => $v['anak_usia_paud_ra'] ?? 0,
                                    'siswa_sd'       => $v['siswa_sd_mi'] ?? 0,
                                    'anak_usia_sd'   => $v['anak_usia_sd_mi'] ?? 0,
                                    'siswa_smp'      => $v['siswa_smp_mts'] ?? 0,
                                    'anak_usia_smp'  => $v['anak_usia_smp_mts'] ?? 0,
                                    'siswa_sma'      => $v['siswa_sma_ma'] ?? 0,
                                    'anak_usia_sma'  => $v['anak_usia_sma_ma'] ?? 0,
                                    'semester'       => $semester,
                                    'tahun'          => $tahun,
                                ];
                        }
                    }

                    if (! empty($insert)) {
                        try {
                            PutusSekolah::insert($insert);
                            return back()->with('success', 'Import data sukses.');
                        } catch (QueryException $ex) {
                            return back()->with('error', 'Import data gagal. ' . $ex->getMessage());
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

    protected function uploadValidation($desa_id, $semester, $tahun)
    {
        return ! PutusSekolah::where('semester', $semester)->where('tahun', $tahun)->where('desa_id', $desa_id)->exists();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $siswa            = PutusSekolah::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Anak Putus Sekolah';
        return view('data.putus_sekolah.edit', compact('page_title', 'page_description', 'siswa'));
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
                'siswa_paud'     => 'required',
                'anak_usia_paud' => 'required',
                'siswa_sd'       => 'required',
                'anak_usia_sd'   => 'required',
                'siswa_smp'      => 'required',
                'anak_usia_smp'  => 'required',
                'siswa_sma'      => 'required',
                'anak_usia_sma'  => 'required',
                'bulan'          => 'required',
                'tahun'          => 'required',
            ]);

            PutusSekolah::find($id)->update($request->all());

            return redirect()->route('data.putus-sekolah.index')->with('success', 'Data berhasil disimpan!');
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
            PutusSekolah::findOrFail($id)->delete();

            return redirect()->route('data.putus-sekolah.index')->with('success', 'Data sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('data.putus-sekolah.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
