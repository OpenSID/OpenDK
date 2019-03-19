<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Models\KategoriKomplain;

class KategoriKomplainController extends Controller
{
    //
    public function index()
    {
        $page_title = 'Kategori Komplain';
        $page_description = 'Daftar Kategori Komplain';
        return view('setting.komplain_kategori.index', compact('page_title', 'page_description'));
    }

    // Get Data Kategori Komplain
    public function getData()
    {
        return DataTables::of(KategoriKomplain::select(['id', 'nama'])->orderBy('id')->get())
            ->addColumn('action', function ($row) {
                $edit_url = route('setting.komplain-kategori.edit', $row->id);
                $delete_url = route('setting.komplain-kategori.destroy', $row->id);

                $data['edit_url'] = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->make();
    }

    // Create Action
    public function create()
    {
        $page_title = 'Tambah';
        $page_description = 'Tambah Kategori Komplain';

        return view('setting.komplain_kategori.create', compact('page_title', 'page_description'));
    }

    // Store Data
    public function store(Request $request)
    {
        try {
            $kategori = new KategoriKomplain($request->all());
            $kategori->slug = str_slug($kategori->nama);

            request()->validate([
                'nama' => 'required',
            ]);

            $kategori->save();
            return redirect()->route('setting.komplain-kategori.index')->with('success', 'Kategori Komplain berhasil dikirim!');

        } catch (Eception $e) {
            return back()->withInput()->with('error', 'Kategori Komplain gagal dikirim!');
        }
    }

    public function edit($id)
    {
        $kategori = KategoriKomplain::findOrFail($id);
        $page_title = 'Edit Kategori';
        $page_description = 'Edit Kategori Komplain ' . $kategori->nama;
        return view('setting.komplain_kategori.edit', compact('page_title', 'page_description', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        // Save Request
        try {
            $kategori = KategoriKomplain::findOrFail($id);
            $kategori->fill($request->all());

            request()->validate([
                'nama' => 'required',
            ]);

            $kategori->save();
            return redirect()->route('setting.komplain-kategori.index')->with('success', 'Kategori Komplain berhasil diupdate!');

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Kategori Komplain gagal diupdate!');
        }
    }

    public function destroy($id)
    {
        try {
            KategoriKomplain::findOrFail($id)->delete();

            return redirect()->route('setting.komplain-kategori.index')->with('success', 'Kategori Komplain berhasil dihapus!');

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Kategori Komplain gagal dihapus!');
        }
    }
}
