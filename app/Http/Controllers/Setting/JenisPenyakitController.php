<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\JenisPenyakit;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function redirect;
use function request;
use function route;
use function view;

class JenisPenyakitController extends Controller
{
    public function index()
    {
        $page_title       = 'Jenis Penyakit';
        $page_description = 'Daftar Jenis Epidemi Penyakit';
        return view('setting.jenis_penyakit.index', compact('page_title', 'page_description'));
    }

    // Get Data Kategori Komplain
    public function getData()
    {
        return DataTables::of(JenisPenyakit::select(['id', 'nama'])->orderBy('id')->get())
            ->addColumn('action', function ($row) {
                $edit_url   = route('setting.jenis-penyakit.edit', $row->id);
                $delete_url = route('setting.jenis-penyakit.destroy', $row->id);

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
        $page_description = 'Tambah Jenis Penyakit';

        return view('setting.jenis_penyakit.create', compact('page_title', 'page_description'));
    }

    // Store Data
    public function store(Request $request)
    {
        try {
            $penyakit = new JenisPenyakit($request->all());

            request()->validate([
                'nama' => 'required',
            ]);

            $penyakit->save();
            return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }
    }

    public function edit($id)
    {
        $penyakit         = JenisPenyakit::findOrFail($id);
        $page_title       = 'Edit Jenis Penyakit';
        $page_description = 'Edit Jenis Penyakit ' . $penyakit->nama;
        return view('setting.jenis_penyakit.edit', compact('page_title', 'page_description', 'penyakit'));
    }

    public function update(Request $request, $id)
    {
        // Save Request
        try {
            $penyakit = JenisPenyakit::findOrFail($id);
            $penyakit->fill($request->all());

            request()->validate([
                'nama' => 'required',
            ]);

            $penyakit->save();
            return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil diupdate!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal diupdate!');
        }
    }

    public function destroy($id)
    {
        try {
            JenisPenyakit::findOrFail($id)->delete();

            return redirect()->route('setting.jenis-penyakit.index')->with('success', 'Data berhasil dihapus!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal dihapus!');
        }
    }
}
