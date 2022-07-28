<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Suplemen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SuplemenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title       = 'Data Suplemen';
        $page_description = 'Daftar Data Suplemen';

        return view('data.data_suplemen.index', compact('page_title', 'page_description'));
    }

    /**
     * Return datatable Data Suplemen
     */
    public function getDataSuplemen()
    {
        if (request()->ajax()) {
            return DataTables::of(Suplemen::withCount('terdata')->get())
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $data['show_url'] = route('data.data-suplemen.show', $row->id);

                    if (! auth()->guest()) {
                        $data['edit_url']   = route('data.data-suplemen.edit', $row->id);
                        $data['delete_url'] = route('data.data-suplemen.destroy', $row->id);
                    }

                    return view('forms.aksi', $data);
                })
                ->make();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title       = 'Data Suplemen';
        $page_description = 'Tambah Data Suplemen';

        return view('data.data_suplemen.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'nama' => 'required',
        ]);

        $request['slug'] = Str::slug($request->nama);

        try {
            Suplemen::create($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Suplemen gagal ditambah!');
        }

        return redirect()->route('data.data-suplemen.index')->with('success', 'Data Suplemen berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $suplemen         = Suplemen::findOrFail($id);
        $page_title       = 'Data Suplemen';
        $page_description = 'Ubah Data Suplemen : ' . $suplemen->nama;

        return view('data.data_suplemen.edit', compact('page_title', 'page_description', 'suplemen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'nama' => 'required',
        ]);

        $request['slug'] = Str::slug($request->nama);

        try {
            Suplemen::findOrFail($id)->update($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Suplemen gagal diubah!');
        }

        return redirect()->route('data.data-suplemen.index')->with('success', 'Data Suplemen berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Suplemen::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('data.data-suplemen.index')->with('error', 'Data Suplemen gagal dihapus!');
        }

        return redirect()->route('data.data-suplemen.index')->with('success', 'Data Suplemen berhasil dihapus!');
    }
}
