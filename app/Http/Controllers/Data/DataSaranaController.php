<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\DataSarana;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportDataSarana;
use App\Imports\ImportDataSarana;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DataSaranaController extends Controller
{
    public function index()
    {
        $page_title = 'Data Sarana';
        $page_description = 'Daftar Sarana Desa';

        $desaSelect = DataDesa::all();

        return view('data.data_sarana.index', compact('page_title', 'page_description', 'desaSelect'));
    }

    public function getData(Request $request)
    {
        $query = DataSarana::with('desa');

        if ($request->desa_id) {
            $query->where('desa_id', $request->desa_id);
        }
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        return datatables()->of($query)
            ->addColumn('desa', function ($row) {
                return $row->desa ? $row->desa->nama : '-';
            })
            ->addColumn('aksi', function ($row) {
                $editUrl = route('data.data-sarana.edit', $row->id);
                $deleteUrl = route('data.data-sarana.destroy', $row->id);

                return view('data.data_sarana.partials.action', compact('editUrl', 'deleteUrl'))->render();
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function create()
    {
        $page_title = "Tambah Sarana";
        $page_description = "Form tambah data sarana";

        $desas = DataDesa::all();
        return view('data.data_sarana.create', compact('page_title', 'page_description', 'desas'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'desa_id' => 'required|integer:desa_id',
                'nama' => 'required|string|max:255',
                'jumlah' => 'required|integer|min:0',
                'kategori' => 'required|string|max:100',
                'keterangan' => 'required|string:max:255',
            ]);
            DataSarana::create($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Sarana gagal disimpan!');
        }
        return redirect()->route('data.data-sarana.index')->with('success', 'Data Sarana berhasil disimpan!');
    }

    public function edit($id)
    {
        $page_title = 'Edit Data Sarana';
        $page_description = 'Ubah informasi sarana desa';

        $sarana = DataSarana::findOrFail($id);
        $desas = DataDesa::all();

        return view('data.data_sarana.edit', compact('page_title', 'page_description', 'sarana', 'desas'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'desa_id' => 'required|integer:desa_id',
                'nama' => 'required|string|max:255',
                'jumlah' => 'required|integer|min:0',
                'kategori' => 'required|string|max:100',
                'keterangan' => 'required|string:max:255',
            ]);
            $sarana = DataSarana::findOrFail($id);
            $sarana->update($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Sarana gagal diperbarui');
        }
        return redirect()->route('data.data-sarana.index')->with('success', 'Data Sarana berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $sarana = DataSarana::findOrFail($id);
            $sarana->delete();
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Sarana gagal dihapus');
        }
        return redirect()->route('data.data-sarana.index')->with('success', 'Data Sarana berhasil dihapus');
    }

    public function export()
    {
        try {
            $search     = request('search');
            $kategori   = request('kategori');
            $startDate  = request('start_date');
            $endDate    = request('end_date');
    
            $data = \App\Models\DataSarana::with('desa')
                ->when($search, fn($q) => $q->where('nama', 'like', "%$search%"))
                ->when($kategori, fn($q) => $q->where('kategori', $kategori))
                ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
                ->latest('id')
                ->get();
    
            return Excel::download(new ExportDataSarana($data, 'Admin Desa'), 'data_sarana.xlsx');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Sarana gagal dihapus');
        }
    }

    public function import()
    {
        $page_title = "Import Sarana";
        $page_description = "Upload data sarana";

        $desas = DataDesa::all();

        return view('data.data_sarana.import', compact('page_title', 'page_description', 'desas'));
    }

    public function importExcel(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv'
            ]);
            Excel::import(new ImportDataSarana, $request->file('file'));
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Sarana gagal diimport');
        }
        return redirect()->route('data.data-sarana.index')->with('success', 'Data Sarana berhasil diimport');
    }

}
