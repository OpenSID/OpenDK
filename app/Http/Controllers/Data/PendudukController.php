<?php

namespace App\Http\Controllers\Data;

use Doctrine\DBAL\Query\QueryException;
use DummyFullModelClass;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Excel;
use Yajra\DataTables\DataTables;
use App\Classes\Data\ImporPenduduk;

class PendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Penduduk $penduduk
     * @return \Illuminate\Http\Response
     */
    public function index(Penduduk $penduduk)
    {
        //
        $page_title = 'Penduduk';
        $page_description = 'Data Penduduk';

        return view('data.penduduk.index', compact('page_title', 'page_description'));
    }

    /**
     *
     * Return datatable Data Penduduk
     */

    public function getPenduduk()
    {
      $query = DB::table('das_penduduk')
            //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
            ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
            ->leftJoin('ref_kawin', 'das_penduduk.status_kawin', '=', 'ref_kawin.id')
            ->leftJoin('ref_pekerjaan', 'das_penduduk.pekerjaan_id', '=', 'ref_pekerjaan.id')
            ->selectRaw('das_penduduk.id, das_penduduk.nik, das_penduduk.nama, das_penduduk.no_kk,
            das_penduduk.alamat, ref_pendidikan_kk.nama as pendidikan,
            das_penduduk.tanggal_lahir, ref_kawin.nama as status_kawin, ref_pekerjaan.nama as pekerjaan')
            ->where('status_dasar', 1);

        return DataTables::of($query->get())
            ->addColumn('action', function ($row) {
                $edit_url = route('data.penduduk.edit', $row->id);
                $delete_url = route('data.penduduk.destroy', $row->id);

                $data['edit_url'] = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->addColumn('tanggal_lahir', function ($row) {
                return convert_born_date_to_age($row->tanggal_lahir);
            })->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Penduduk $penduduk
     * @return \Illuminate\Http\Response
     */
    public function create(Penduduk $penduduk)
    {
        //
        $page_title = 'Tambah';
        $page_description = 'Tambah Data Penduduk';

        return view('data.penduduk.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save Request
        try {
            $penduduk = new Penduduk($request->all());
            $penduduk->id_rtm = 0;
            $penduduk->rtm_level = 0;
            $penduduk->pendidikan_id = 0;
            $penduduk->id_cluster = 0;
            $penduduk->status_dasar = 1;

            request()->validate([
                'nama' => 'required',
                'nik' => 'required',
                'kk_level' => 'required',
                'sex' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'agama_id' => 'required',
                'pendidikan_kk_id' => 'required',
                'pendidikan_sedang_id' => 'required',
                'pekerjaan_id' => 'required',
                'status_kawin' => 'required',
                'warga_negara_id' => 'required',
            ]);

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $fileName = $file->getClientOriginalName();
                $request->file('foto')->move("storage/penduduk/foto/", $fileName);
                $penduduk->foto = 'storage/penduduk/foto/' . $fileName;
            }

            $penduduk->save();
            return redirect()->route('data.penduduk.index')->with('success', 'Penduduk berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Penduduk gagal disimpan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penduduk $penduduk
     * @param  \DummyFullModelClass $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function show(Penduduk $penduduk, DummyModelClass $DummyModelVariable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penduduk $penduduk
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penduduk = Penduduk::findOrFail($id);
        if ($penduduk->foto == '') {
            $penduduk->file_struktur_organisasi = 'http://placehold.it/120x150';
        }
        $page_title = 'Ubah';
        $page_description = 'Ubah Penduduk: ' . ucwords(strtolower($penduduk->nama));


        return view('data.penduduk.edit', compact('page_title', 'page_description', 'penduduk'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Save Request
        try {
            $penduduk = Penduduk::where('id', $id)->first();
            $penduduk->fill($request->all());

            request()->validate([
                'nama' => 'required',
                'nik' => 'required',
                'kk_level' => 'required',
                'sex' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'agama_id' => 'required',
                'pendidikan_kk_id' => 'required',
                'pendidikan_sedang_id' => 'required',
                'pekerjaan_id' => 'required',
                'status_kawin' => 'required',
                'warga_negara_id' => 'required',
                'foto'=>'image|mimes:png,bmp,gif,jpg,jpeg|max:1024'
            ]);

            if ($request->file('foto') == "") {
                $penduduk->foto = $penduduk->foto;
            } else {
                $file = $request->file('foto');
                $fileName = $file->getClientOriginalName();
                $request->file('foto')->move("storage/penduduk/foto/", $fileName);
                $penduduk->foto = 'storage/penduduk/foto/' . $fileName;
            }

            $penduduk->update();


            return redirect()->route('data.penduduk.index')->with('success', 'Penduduk berhasil disimpan!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Penduduk gagal disimpan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Penduduk::findOrFail($id)->delete();

            return redirect()->route('data.penduduk.index')->with('success', 'Penduduk sukses dihapus!');

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('data.penduduk.index')->with('error', 'Penduduk gagal dihapus!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $page_title = 'Import';
        $page_description = 'Import Data Penduduk';

        $list_desa = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', config('app.default_profile'))->get();
        return view('data.penduduk.import', compact('page_title', 'page_description', 'list_desa'));
    }

    /**
     * Impor data penduduk dari file Excel.
     * Kalau penduduk sudah ada (berdasarkan NIK), update dengan data yg diimpor
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function importExcel(Request $request)
    {
        try {
            $impor_penduduk = new ImporPenduduk($request);
            $impor_penduduk->insertOrUpdate();
        } catch (\Exception $e){
            return back()->with('error', 'Import data gagal. '.$e->getMessage());
        }

        return back()->with('success', 'Import data sukses.');
    }
}
