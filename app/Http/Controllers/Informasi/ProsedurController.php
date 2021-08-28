<?php

namespace App\Http\Controllers\Informasi;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\Prosedur;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Flysystem\Exception;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function redirect;
use function request;
use function route;
use function view;

class ProsedurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Prosedur';
        $page_description = 'Kumpulan SOP ' .$this->sebutan_wilayah;
        $prosedurs        = Prosedur::latest()->paginate(10);

        return view('informasi.prosedur.index', compact(['page_title', 'page_description', 'prosedurs']))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Tambah Prosedur';
        return view('informasi.prosedur.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'judul_prosedur' => 'required',
            'file_prosedur'  => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);
        $prosedur = new Prosedur($request->input());

        if ($request->hasFile('file_prosedur')) {
            $file     = $request->file('file_prosedur');
            $fileName = $file->getClientOriginalName();
            $path     = "storage/regulasi/";
            $request->file('file_prosedur')->move($path, $fileName);
            $prosedur->file_prosedur = $path . $fileName;
            $prosedur->mime_type     = $file->getClientOriginalExtension();
        }
        $prosedur->save();

        return redirect()->route('informasi.prosedur.index')->with('success', 'Prosedur berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $prosedur   = Prosedur::find($id);
        $page_title = 'Detail Prosedur :' . $prosedur->judul_prosedur;

        return view('informasi.prosedur.show', compact('page_title', 'prosedur'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $prosedur         = Prosedur::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Prosedur : ' . $prosedur->judul_prosedur;

        return view('informasi.prosedur.edit', compact('page_title', 'page_description', 'prosedur'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function download($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $prosedur = Prosedur::findOrFail($id);
            $prosedur->fill($request->all());

            request()->validate([
                'judul_prosedur' => 'required',
                'file_prosedur'  => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
            ]);

            if ($request->hasFile('file_prosedur')) {
                $file     = $request->file('file_prosedur');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/regulasi/";
                $request->file('file_prosedur')->move($path, $fileName);
                $prosedur->file_prosedur = $path . $fileName;
                $prosedur->mime_type     = $file->getClientOriginalExtension();
            }

            $prosedur->save();

            return redirect()->route('informasi.prosedur.index')->with('success', 'Data Prosedur berhasil disimpan!');
        } catch (Exception $e) {
            return back()->with('error', 'Data Prosedur gagal disimpan!' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        Prosedur::find($id)->delete();
        return redirect()->route('informasi.prosedur.index')->with('success', 'Prosedur Berhasil dihapus!');
    }

    /**
     * Get datatable
     */
    public function getDataProsedur()
    {
        return DataTables::of(Prosedur::select('id', 'judul_prosedur'))
            ->addColumn('action', function ($row) {
                $show_url   = route('informasi.prosedur.show', $row->id);
                $edit_url   = route('informasi.prosedur.edit', $row->id);
                $delete_url = route('informasi.prosedur.destroy', $row->id);

                $data['show_url'] = $show_url;

                if (! Sentinel::guest()) {
                    $data['edit_url']   = $edit_url;
                    $data['delete_url'] = $delete_url;
                }

                return view('forms.action', $data);
            })
            ->editColumn('judul_prosedur', function ($row) {
                return $row->judul_prosedur;
            })->make();
    }
}
