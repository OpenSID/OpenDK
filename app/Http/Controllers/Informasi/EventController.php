<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Http\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Event';
        $page_description = 'Daftar Event';
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
        $page_title       = 'Event';
        $page_description = 'Tambah Data';

        return view('informasi.event.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'event_name' => 'required',
            'start'      => 'required',
            'end'        => 'required',
            'attendants' => 'required',
        ]);

        try {
            $event = new Event($request->input());
            $event->status = 'OPEN';
            $event->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Simpan Event gagal!');
        }

        return redirect()->route('informasi.event.index')->with('success', 'Event berhasil disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $page_title       = 'Event';
        $page_description = 'Ubah Data';
        $event            = Event::FindOrFail($id);

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
        request()->validate([
            'event_name' => 'required',
            'start'      => 'required',
            'end'        => 'required',
            'attendants' => 'required',
            'attachment' => 'file|mimes:jpeg,png,jpg,gif,svg,xlsx,xls,doc,docx,pdf,ppt,pptx|max:2048',
        ]);

        try {
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
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Ubah Event gagal!');
        }

        return redirect()->route('informasi.event.index')->with('success', 'Ubah Event sukses!');
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
        } catch (Exception $e) {
            return redirect()->route('informasi.event.index')->with('error', 'Event gagal dihapus!');
        }

        return redirect()->route('informasi.event.index')->with('success', 'Event sukses dihapus!');
    }
}
