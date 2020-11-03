<?php

namespace App\Http\Controllers\INformasi;

use app\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use function back;
use function compact;
use function redirect;
use function request;
use function view;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Counter::count('informasi.event.index');

        $page_title       = 'Event';
        $page_description = 'Kumpulan Event Kecamatan';
        $events           = Event::getOpenEvents();

        return view('informasi.event.index', compact('page_title', 'page_description', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah event baru';

        return view('informasi.event.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $event = new Event($request->input());
            request()->validate([
                'event_name' => 'required',
                'start'      => 'required',
                'end'        => 'required',
                'attendants' => 'required',
            ]);
            $event->status = 'OPEN';
            $event->save();
            return redirect()->route('informasi.event.index')->with('success', 'Event berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Simpan Event gagal!');
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
        $page_title       = 'Ubah';
        $page_description = 'Edit Event';
        $event            = Event::find($id);

        return view('informasi.event.edit', compact('page_title', 'page_description', 'event'));
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
            request()->validate([
                'event_name' => 'required',
                'start'      => 'required',
                'end'        => 'required',
                'attendants' => 'required',
                'attachment' => 'file|mimes:jpeg,png,jpg,gif,svg,xlsx,xls,doc,docx,pdf,ppt,pptx|max:2048',
            ]);

            $event = Event::findOrFail($id);
            $event->fill($request->all());

            if ($request->hasFile('attachment')) {
                $lampiran = $request->file('attachment');
                $fileName = $lampiran->getClientOriginalName();
                $path     = "storage/event/" . $event->id . '/';
                $request->file('attachment')->move($path, $fileName);
                $event->attachment = $path . $fileName;
            }

            $event->save();

            return redirect()->route('informasi.event.index')->with('success', 'Update Event sukses!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Update Event gagal!');
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
            Event::findOrFail($id)->delete();

            return redirect()->route('informasi.event.index')->with('success', 'Event sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('informasi.event.index')->with('error', 'Event gagal dihapus!');
        }
    }
}
