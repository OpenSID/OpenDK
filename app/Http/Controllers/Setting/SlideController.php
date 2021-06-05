<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide;;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Response;
use League\Flysystem\Exception;
use Yajra\DataTables\DataTables;

class SlideController extends Controller
{
    //
    public function index(){
        $page_title       = 'Slide';
        $page_description = 'Daftar Slide';
        return view('setting.slide.index', compact('page_title','page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Tambah Slide';
        return view('setting.slide.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'judul'     => 'required',
            'deskripsi' => 'required',
            'gambar'    => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);
        $slide = new Slide($request->input());

        if ($request->hasFile('gambar')) {
            $file     = $request->file('gambar');
            $fileName = $file->getClientOriginalName();
            $path     = "storage/slide/";
            $request->file('gambar')->move($path, $fileName);
            $slide->gambar = $path . $fileName;
        }
        $slide->save();

        return redirect()->route('setting.slide.index')->with('success', 'Slide berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $slide   = Slide::find($id);
        $page_title = 'Detail Slide :' . $slide->judul;

        return view('setting.slide.show', compact('page_title', 'slide'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $slide         = Slide::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Slide : ' . $slide->judul_prosedur;

        return view('setting.slide.edit', compact('page_title', 'page_description', 'slide'));
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
            $slide = Slide::findOrFail($id);
            $slide->fill($request->all());

            request()->validate([
                'judul' => 'required',
                'deskripsi' => 'required|max:100',
                'gambar'  => 'required|file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
            ]);

            if ($request->hasFile('gambar')) {
                $file     = $request->file('gambar');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/slide/";
                $request->file('gambar')->move($path, $fileName);
                $slide->gambar = $path . $fileName;
            }

            $slide->save();

            return redirect()->route('setting.slide.index')->with('success', 'Data Slide berhasil disimpan!');
        } catch (Exception $e) {
            return back()->with('error', 'Data Slide gagal disimpan!' . $e->getMessage());
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
        Slide::find($id)->delete();
        return redirect()->route('setting.slide.index')->with('success', 'Slide Berhasil dihapus!');
    }
     /**
     * Get datatable
     */
    public function getData()
    {
        return DataTables::of(Slide::select('id', 'judul','deskripsi'))
            ->addColumn('action', function ($row) {
                // $show_url   = route('setting.slide.show', $row->id);
                $edit_url   = route('setting.slide.edit', $row->id);
                $delete_url = route('setting.slide.destroy', $row->id);

                // $data['show_url'] = $show_url;

                if (! Sentinel::guest()) {
                    $data['edit_url']   = $edit_url;
                    $data['delete_url'] = $delete_url;
                }

                return view('forms.action', $data);
            })
            ->editColumn('judul', function ($row) {
                return $row->judul;
                return $row->deskripsi;
            })->make();
    }
}
