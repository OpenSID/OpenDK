<?php

namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request as RequestFacade;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Doctrine\DBAL\Query\QueryException;

use function config;
use function convert_born_date_to_age;
class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Pegawai $pegawai)
    {
        $page_title       = 'Pegawai';
        $page_description = 'Data Pegawai';

        return view('data.pegawai.index', compact('page_title', 'page_description'));
    }

    /**
     * Return datatable Data Pegawai.
     *
     * @param Request $request
     * @return DataTables
     */
    public function getPegawai(Request $request)
    {
        $pegawai = $request->input('nama_pegawai');

        $query = DB::table('das_pegawai')
            ->leftJoin('das_jabatan', 'das_pegawai.jabatan_id', '=', 'das_jabatan.id')
            ->select([
                'das_pegawai.id',
                'das_pegawai.foto',
                'das_pegawai.nip',
                'das_pegawai.status',
                'das_pegawai.nama_pegawai',
                'das_pegawai.tanggal_lahir',
                'das_jabatan.nama_jabatan as nama_jabatan',
            ])
            ->when($pegawai, function ($query) use ($pegawai) {
                $query->where('nama_pegawai', $pegawai);
            });
            // ->where('status','aktif');

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $edit_url   = route('data.pegawai.edit', $row->id);
                $delete_url = route('data.pegawai.destroy', $row->id);

                $data['edit_url']   = $edit_url;
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
     * @return Response
     */
    public function create(Pegawai $pegawai)
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Data Pegawai';
        $jabatan = Jabatan::pluck('nama_jabatan','id');
        return view('data.pegawai.create', compact('page_title','jabatan', 'page_description'));
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
            $pegawai           = new Pegawai($request->all());
            request()->validate([
            'nama_pegawai'     => 'required|max:100',
            'jenis_kelamin'    => 'required',
            'agama_id'         => 'required',
            'tempat_lahir'     => 'required',
            'tanggal_lahir'    => 'required',
            'status_kawin_id'  => 'required',
            'nik'              => 'required',
            'pendidikan'       => 'required',
            'tamat_pendidikan' => 'required',
            'telepon'          => 'required',
            'alamat'           => 'required',
            'foto'             => 'nullable|mimes:jpg,png,jpeg|max:1024',
            'foto.*'           => 'mimes:jpg,png,jpeg',
            ]);
            
            // dd($request);
            if ($request->hasFile('foto')) {
                $file     = $request->file('foto');
                $fileName = $file->getClientOriginalName();
                $request->file('foto')->move("storage/pegawai/foto/", $fileName);
                $pegawai->foto = $fileName;
            }
             $pegawai->save();
            return redirect()->route('data.pegawai.index')->with('success', 'Pegawai berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Pegawai gagal disimpan!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Pegawai $pegawai
     * @return Response
     */
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $jabatan = Jabatan::pluck('nama_jabatan','id');
        if ($pegawai->foto == '') {
            $pegawai->foto = 'http://placehold.it/120x150';
        }
        $page_title       = 'Ubah';
        $page_description = 'Ubah Pegawai: ' . ucwords(strtolower($pegawai->nama_pegawai));

        return view('data.pegawai.edit', compact('page_title', 'jabatan','page_description', 'pegawai'));
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
            $pegawai = Pegawai::where('id', $id)->first();
            $pegawai->fill($request->all());

            request()->validate([
                'nama_pegawai'     => 'required|max:100',
                'jenis_kelamin'    => 'required',
                'agama_id'         => 'required',
                'tempat_lahir'     => 'required',
                'tanggal_lahir'    => 'required',
                'status_kawin_id'  => 'required',
                'nomor_ktp'        => 'required',
                'pendidikan'       => 'required',
                'tamat_pendidikan' => 'required',
                'telepon'          => 'required',
                'alamat'           => 'required',
                'foto'             => 'nullable|mimes:jpg,png,jpeg|max:1024',
                'foto.*'           => 'mimes:jpg,png,jpeg',
            ]);

            if ($request->file('foto') == "") {
                $pegawai->foto = $pegawai->foto;
            } else {
                $file     = $request->file('foto');
                $fileName = $file->getClientOriginalName();
                $request->file('foto')->move("storage/pegawai/foto/", $fileName);
                $pegawai->foto = $fileName;
            }

            $pegawai->update();

            return redirect()->route('data.pegawai.index')->with('success', 'Pegawai berhasil disimpan!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Pegawai gagal disimpan!');
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
            Pegawai::findOrFail($id)->delete();

            return redirect()->route('data.pegawai.index')->with('success', 'Pegawai sukses dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('data.pegawai.index')->with('error', 'Pegawai gagal dihapus!');
        }
    }

}
