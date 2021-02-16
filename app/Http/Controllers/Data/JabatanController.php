<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jabatan;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
class JabatanController extends Controller
{

    public function index()
    {
        $page_title       = 'Jabatan';
        $page_description = 'Data Jabatan';
        $jabatans = Jabatan::all();
        return view('data.jabatan.index', compact('jabatans','page_title','page_description'));
    }

     /**
     * Return datatable Data Pegawai.
     *
     * @param Request $request
     * @return DataTables
     */
    public function getJabatan(Request $request)
    {
        $query = DB::table('das_jabatan')
            ->select(['id','nama_jabatan']);

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $edit_url   = route('data.jabatan.edit', $row->id);
                $delete_url = route('data.jabatan.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Data Jabatan';
        $jabatans = Jabatan::all();
        $parent_Jabatan = [];
        foreach($jabatans as $jabatan){
            $parent_Jabatan[$jabatan->id] = $jabatan->nama_jabatan;
        }
        return view('data.jabatan.create', compact('parent_Jabatan','page_title','page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $Jabatan = $request->validate([
            'nama_jabatan' => 'required|max:50',
            'parent_id' => 'nullable'
        ]);

        $Jabatan['parent_id'] = empty($Jabatan['parent_id']) ? 0 : $Jabatan['parent_id'];
        Jabatan::create($Jabatan);

        return redirect()->back()->with('success', 'Data Jabatan Sukses Tesimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $jabatan     = Jabatan::find($id);
        $parent_jabatan = Jabatan::pluck('nama_jabatan','id');
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Jabatan';

        return view('data.jabatan.edit', compact('jabatan', 'page_title','parent_jabatan', 'page_description'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $Jabatan = $request->validate([
            'nama_jabatan'  => 'required|max:50',
            'parent_id' => 'nullable',
        ]);
        
        $Jabatan['parent_id'] = empty($Jabatan['parent_id']) ? 0 : $Jabatan['parent_id'];
        Jabatan::whereId($id)->update($Jabatan);

        return redirect()->route('data.jabatan.index')->with('success', 'Data Jabatan Sukses Terupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();
        return redirect()->route('data.jabatan.index')->with('success', 'Data Jabatan Berhasil Dihapus');
    }
}
