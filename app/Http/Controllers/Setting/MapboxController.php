<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Http\Requests\MapboxRequest;
use App\Models\Mapbox;

class MapboxController extends Controller
{
    public function index()
    {
        $page_title = 'MapBox';
        $page_description = 'Setting Akses Token Map Box';

        return view('setting.mapbox.index', compact('page_title', 'page_description'));
    }

    public function getData()
    {
        return DataTables::of(Mapbox::all())
            ->addColumn('aksi', function ($row) {
                $data['edit_url'] = route('setting.mapbox.edit', $row->id);
                $data['delete_url'] = route('setting.mapbox.destroy', $row->id);

                return view('forms.aksi', $data);
            })
            ->editColumn('default_map', function ($row) {
                return $row->default_map;
            })->make();
    }

    public function create()
    {
        $mapbox = null;
        $page_title = 'Map Box';
        $page_description = 'Tambah Token';

        return view('setting.mapbox.create', compact('page_title', 'page_description', 'mapbox'));
    }

    public function store(MapboxRequest $request)
    {
        try {
            $input = $request->validated();
            Mapbox::create($input);
    
            // $mapbox = new Mapbox();
            // $mapbox->token = $request->token;
            // $mapbox->default_map = $request->default_map;
            // $mapbox->save();
            // dd($mapbox);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Token gagal ditambah!');
        }

        return redirect()->route('setting.mapbox.index')->with('success', 'Token berhasil ditambah!');
    }

    public function show(Mapboox $mapbox)
    {
        $page_title = 'Detail Token :' . $slide->judul;

        return view('setting.mapbox.show', compact('page_title', 'page_description', 'mapbox'));
    }

    public function edit(Mapbox $mapbox)
    {
        $page_title = 'Map Boox';
        $page_description = 'Ubah Token : ' . $mapbox->token;

        return view('setting.mapbox.edit', compact('page_title', 'page_description', 'mapbox'));
    }

    public function update(MapboxRequest $request, Mapbox $mapbox)
    {
        try {
            $input = $request->validated();
           
            $mapbox->update($input);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Data Token gagal disimpan!');
        }

        return redirect()->route('setting.mapbox.index')->with('success', 'Data Token berhasil disimpan!');
    }

    public function destroy(Mapbox $mapbox)
    {
        try {
            $mapbox->delete();
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Token gagal dihapus!');
        }

        return redirect()->route('setting.mapbox.index')->with('success', 'Token berhasil dihapus!');
    }
}
