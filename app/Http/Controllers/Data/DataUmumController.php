<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataUmum;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function config;
use function json_encode;
use function redirect;
use function request;
use function route;
use function strtolower;
use function ucwords;
use function view;

class DataUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       /* $page_title = 'Data Umum';
        $page_description = 'Data Umum Kecamatan';
        return view('data.data_umum.index', compact('page_title', 'page_description'));*/
        $data_umum        = DataUmum::where('kecamatan_id', config('app.default_profile'))->first();
        $page_title       = 'Ubah Data Umum';
        $page_description = 'Kecamatan ' . ucwords(strtolower($data_umum->kecamatan->nama));

        return view('data.data_umum.edit', compact('page_title', 'page_description', 'data_umum'));
    }

    /**
     * Return datatable Data Umum
     */

    public function getDataUmum()
    {
        return DataTables::of(DataUmum::with(['Kecamatan'])->select(['id', 'kecamatan_id', 'tipologi', 'luas_wilayah', 'jumlah_penduduk', 'bts_wil_utara', 'bts_wil_timur', 'bts_wil_selatan', 'bts_wil_barat'])->get())
            ->addColumn('action', function ($data) {
                $edit_url = route('data.data-umum.edit', $data->id);

                $data['edit_url'] = $edit_url;

                return view('forms.action', $data);
            })
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Buat';
        $page_description = 'Data Umum Baru';
        $data_umum        = new DataUmum();

        return view('data.data_umum.create', compact('page_title', 'page_description', 'data_umum'));
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
            $profil = new DataUmum($request->input());
            request()->validate([
                'kecamatan_id'           => 'required',
                'tipologi'               => 'required',
                'luas_wilayah'           => 'required',
                'jumlah_penduduk'        => 'required',
                'jml_laki_laki'          => 'required',
                'jml_perempuan'          => 'required',
                'bts_wil_utara'          => 'required',
                'bts_wil_timur'          => 'required',
                'bts_wil_selatan'        => 'required',
                'bts_wil_barat'          => 'required',
                'jml_puskesmas'          => 'required',
                'jml_puskesmas_pembantu' => 'required',
                'jml_posyandu'           => 'required',
                'jml_pondok_bersalin'    => 'required',
                'jml_paud'               => 'required',
                'jml_sd'                 => 'required',
                'jml_smp'                => 'required',
                'jml_sma'                => 'required',
                'jml_masjid_besar'       => 'required',
                'jml_gereja'             => 'required',
                'jml_pasar'              => 'required',
                'jml_balai_pertemuan'    => 'required',
                'kepadatan_penduduk'     => 'required',
            ]);
            $profil->save();
            return redirect()->route('data.data-umum.index')->with('success', 'Data Umum berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Data Umum gagal disimpan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return json_encode(Profil::getProfilTanpaDataUmum());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data_umum        = DataUmum::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Data Umum Kecamatan ' . ucwords(strtolower($data_umum->kecamatan->nama));

        return view('data.data_umum.edit', compact('page_title', 'page_description', 'data_umum'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            request()->validate([
                'kecamatan_id'           => 'required',
                'tipologi'               => 'required',
                'luas_wilayah'           => 'required',
                'jumlah_penduduk'        => 'required',
                'jml_laki_laki'          => 'required',
                'jml_perempuan'          => 'required',
                'bts_wil_utara'          => 'required',
                'bts_wil_timur'          => 'required',
                'bts_wil_selatan'        => 'required',
                'bts_wil_barat'          => 'required',
                'jml_puskesmas'          => 'required',
                'jml_puskesmas_pembantu' => 'required',
                'jml_posyandu'           => 'required',
                'jml_pondok_bersalin'    => 'required',
                'jml_paud'               => 'required',
                'jml_sd'                 => 'required',
                'jml_smp'                => 'required',
                'jml_sma'                => 'required',
                'jml_masjid_besar'       => 'required',
                'jml_mushola'            => 'required',
                'jml_gereja'             => 'required',
                'jml_pasar'              => 'required',
                'jml_balai_pertemuan'    => 'required',
                'kepadatan_penduduk'     => 'required',
            ]);

            DataUmum::find($id)->update($request->all());

            return redirect()->route('data.data-umum.index')->with('success', 'Update Data Umum sukses!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Update Data Umum gagal!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // Delete Data UMum
        try {
            DataUmum::findOrFail($id)->delete();

            return redirect()->route('data.data-umum.index')->with('success', 'Data Umum sukses dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('data.data-umum.index')->with('error', 'Data Umum gagal dihapus!');
        }
    }
}
