<?php

namespace App\Http\Controllers\Data;

use App\Enums\KategoriSarana;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataSaranaRequest;
use App\Http\Requests\DataSaranaImportRequest;
use App\Models\DataDesa;
use App\Models\DataSarana;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportDataSarana;
use App\Imports\ImportDataSarana;
use App\Services\DesaService;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DataSaranaController extends Controller
{
    public function index()
    {
        $page_title = 'Data Sarana';
        $page_description = 'Daftar Sarana Desa';

        $desaSelect = (new DesaService())->listDesa();

        return view('data.data_sarana.index', compact('page_title', 'page_description', 'desaSelect'));
    }

    public function getData(Request $request)
    {
        $isExcel = $request->action == 'excel' ? true : false;
        if ($isExcel) {
            $paramDatatable = json_decode($request->get('params'), 1);
            $request->merge($paramDatatable);
            $request->merge(['params' => null]);
        }        
        $desaId = $request->desa_id;
        $kategori = $request->kategori;
        $query = DataTables::of(DataSarana::with('desa')->when($desaId, static fn($q) => $q->whereDesaId($desaId))->when($kategori, static fn($q) => $q->whereKategori($kategori)));
        if ($isExcel){ 
            $query->filtering();
            return Excel::download(new ExportDataSarana($query->results()), 'Data-sarana.xlsx');
        }
        return $query->addColumn('desa', function ($row) {
            return $row->desa ? $row->desa->nama : '-';
        })->editColumn('kategori', function ($row) {
            return KategoriSarana::getDescription($row->kategori);
        })->addColumn('aksi', function ($row) {
            $editUrl = route('data.data-sarana.edit', $row->id);
            $deleteUrl = route('data.data-sarana.destroy', $row->id);

            return view('data.data_sarana.partials.action', compact('editUrl', 'deleteUrl'))->render();
        })->rawColumns(['aksi'])->make(true);
    }


    public function create()
    {
        $page_title = "Tambah Sarana";
        $page_description = "Form tambah data sarana";

        $desas = (new DesaService())->listDesa();
        return view('data.data_sarana.create', compact('page_title', 'page_description', 'desas'));
    }

    public function store(DataSaranaRequest $request)
    {
        try {
            DataSarana::create($request->validated());
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
        $desas = (new DesaService())->listDesa();

        return view('data.data_sarana.edit', compact('page_title', 'page_description', 'sarana', 'desas'));
    }

    public function update(DataSaranaRequest $request, $id)
    {
        try {
            $sarana = DataSarana::findOrFail($id);
            $sarana->update($request->validated());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw $e;
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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Sarana gagal dihapus');
        }
        return redirect()->route('data.data-sarana.index')->with('success', 'Data Sarana berhasil dihapus');
    }

    public function import()
    {
        $page_title = "Import Sarana";
        $page_description = "Upload data sarana";        

        return view('data.data_sarana.import', compact('page_title', 'page_description'));
    }

    public function importExcel(DataSaranaImportRequest $request)
    {
        try {            
            Excel::import(new ImportDataSarana($this->isDatabaseGabungan() ? 'local' : 'gabungan'), $request->file('file'));
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Sarana gagal diimport');
        }
        return redirect()->route('data.data-sarana.index')->with('success', 'Data Sarana berhasil diimport');
    }
}
