<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\TipePotensi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function redirect;
use function request;
use function route;
use function str_slug;
use function view;

class TipePotensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Kategori Potensi';
        $page_description = 'Daftar Kategori Potensi';
        return view('setting.tipe_potensi.index', compact('page_title', 'page_description'));
    }

    // Get Data Tipe Potensi
    public function getData()
    {
        return DataTables::of(TipePotensi::select(['id', 'nama_kategori'])->orderBy('id')->get())
            ->addColumn('action', function ($row) {
                $edit_url   = route('setting.tipe-potensi.edit', $row->id);
                $delete_url = route('setting.tipe-potensi.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

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
        $page_title       = 'Tambah';
        $page_description = 'Tambah Kategori Potensi';

        return view('setting.tipe_potensi.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $tipe       = new TipePotensi($request->all());
            $tipe->slug = str_slug($tipe->nama_kategori);

            request()->validate([
                'nama_kategori' => 'required',
            ]);

            $tipe->save();
            return redirect()->route('setting.tipe-potensi.index')->with('success', 'Kategori Potensi berhasil dikirim!');
        } catch (Eception $e) {
            return back()->withInput()->with('error', 'Tipe Potensi gagal dikirim!');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tipe             = TipePotensi::findOrFail($id);
        $page_title       = 'Edit';
        $page_description = 'Edit Kategori Potensi ' . $tipe->nama_kategori;
        return view('setting.tipe_potensi.edit', compact('page_title', 'page_description', 'tipe'));
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
            $tipe = TipePotensi::findOrFail($id);
            $tipe->fill($request->all());
            $tipe->slug = str_slug($tipe->nama_kategori);

            request()->validate([
                'nama_kategori' => 'required',
            ]);

            $tipe->save();
            return redirect()->route('setting.tipe-potensi.index')->with('success', 'Kategori Potensi berhasil diupdate!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Kategori Potensi gagal diupdate!');
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
        try {
            TipePotensi::findOrFail($id)->delete();

            return redirect()->route('setting.tipe-potensi.index')->with('success', 'Kategori Potensi berhasil dihapus!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Tipe Potensi gagal dihapus!');
        }
    }
}
