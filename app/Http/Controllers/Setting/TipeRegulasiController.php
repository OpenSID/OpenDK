<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\TipeRegulasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function redirect;
use function request;
use function route;
use function str_slug;
use function view;

class TipeRegulasiController extends Controller
{
    public function index()
    {
        $page_title       = 'Tipe Regulasi';
        $page_description = 'Daftar TIpe Regulasi';
        return view('setting.tipe_regulasi.index', compact('page_title', 'page_description'));
    }

    // Get Data Kategori Komplain
    public function getData()
    {
        return DataTables::of(TipeRegulasi::select(['id', 'nama'])->orderBy('id')->get())
            ->addColumn('action', function ($row) {
                $edit_url   = route('setting.tipe-regulasi.edit', $row->id);
                $delete_url = route('setting.tipe-regulasi.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->make();
    }

    // Create Action
    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Tipe Regulasi';

        return view('setting.tipe_regulasi.create', compact('page_title', 'page_description'));
    }

    // Store Data
    public function store(Request $request)
    {
        try {
            $tipe       = new TipeRegulasi($request->all());
            $tipe->slug = str_slug($tipe->nama);

            request()->validate([
                'nama' => 'required',
            ]);

            $tipe->save();
            return redirect()->route('setting.tipe-regulasi.index')->with('success', 'Tipe Regulasi berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Tipe Regulasi gagal dikirim!');
        }
    }

    public function edit($id)
    {
        $tipe             = TipeRegulasi::findOrFail($id);
        $page_title       = 'Edit';
        $page_description = 'Edit Tipe Regulasi' . $tipe->nama;
        return view('setting.tipe_regulasi.edit', compact('page_title', 'page_description', 'tipe'));
    }

    public function update(Request $request, $id)
    {
        // Save Request
        try {
            $tipe = TipeRegulasi::findOrFail($id);
            $tipe->fill($request->all());

            request()->validate([
                'nama' => 'required',
            ]);

            $tipe->save();
            return redirect()->route('setting.tipe-regulasi.index')->with('success', 'Tipe Regulasi berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Tipe Regulasi gagal diupdate!');
        }
    }

    public function destroy($id)
    {
        try {
            TipeRegulasi::findOrFail($id)->delete();

            return redirect()->route('setting.tipe-regulasi.index')->with('success', 'Tipe Regulasi berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Tipe Regulasi gagal dihapus!');
        }
    }
}
