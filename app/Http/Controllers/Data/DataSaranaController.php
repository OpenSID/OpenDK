<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\DataSarana;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportDataSarana;
use Illuminate\Support\Facades\DB;

class DataSaranaController extends Controller
{
    public function index()
    {
        $page_title = 'Data Sarana';
        $page_description = 'Daftar Sarana Desa';

        $search     = request('search');
        $kategori   = request('kategori');
        $startDate  = request('start_date');
        $endDate    = request('end_date');

        $desas = DataDesa::with(['saranas' => function ($query) use ($search, $kategori, $startDate, $endDate) {
                $query
                    ->when($search, fn($q) => $q->where('nama', 'like', "%$search%"))
                    ->when($kategori, fn($q) => $q->where('kategori', $kategori))
                    ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
                    ->latest('id');
            }])
            ->paginate(10);

        $rekapKategori = DB::table('das_data_sarana')
            ->select('kategori', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori'); 

        return view('data.data_sarana.index', compact('page_title', 'page_description', 'desas', 'rekapKategori'));
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
        $request->validate([
            'desa_id' => 'required|integer:desa_id',
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kategori' => 'required|string|max:100',
            'keterangan' => 'required|string:max:255',
        ]);

        DataSarana::create($request->all());

        return redirect()->route('data.data-sarana.index')->with('success', 'Data sarana berhasil ditambahkan.');
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
        $request->validate([
            'desa_id' => 'required|integer:desa_id',
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kategori' => 'required|string|max:100',
            'keterangan' => 'required|string:max:255',
        ]);

        $sarana = DataSarana::findOrFail($id);
        $sarana->update($request->all());

        return redirect()->route('data.data-sarana.index')->with('success', 'Data sarana berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sarana = DataSarana::findOrFail($id);
        $sarana->delete();

        return redirect()->route('data.data-sarana.index')->with('success', 'Data sarana berhasil dihapus.');
    }

    public function export()
    {
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
    }

}
