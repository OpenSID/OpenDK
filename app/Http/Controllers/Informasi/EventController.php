<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $page_title       = 'Event';
        $page_description = 'Daftar Event';
        $events           = Event::getOpenEvents();

        return view('informasi.event.index', compact('page_title', 'page_description', 'events'));
    }

    public function create()
    {
        $page_title       = 'Event';
        $page_description = 'Tambah Data';

        return view('informasi.event.create', compact('page_title', 'page_description'));
    }

    public function store(EventRequest $request)
    {
        try {
            $input = $request->input();
            $input['status'] = 'OPEN';
            Event::create($input);
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Simpan Event gagal!');
        }

        return redirect()->route('informasi.event.index')->with('success', 'Event berhasil disimpan!');
    }

    public function edit(Event $event)
    {
        $page_title       = 'Event';
        $page_description = 'Ubah Data';

        return view('informasi.event.edit', compact('page_title', 'page_description', 'event'));
    }

    public function update(EventRequest $request, Event $event)
    {
        try {
            $input = $request->all();

            if ($request->hasFile('attachment')) {
                $lampiran = $request->file('attachment');
                $fileName = $lampiran->getClientOriginalName();
                $path     = "storage/event/" . $event->id . '/';
                $lampiran->move($path, $fileName);
                unlink(base_path('public/' . $event->file_dokumen));

                $input['attachment'] = $path . $fileName;
            }
            $event->update($input);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Ubah Event gagal!');
        }

        return redirect()->route('informasi.event.index')->with('success', 'Ubah Event sukses!');
    }

    public function destroy(Event $event)
    {
        try {
            if ($event->delete()) {
                unlink(base_path('public/' . $event->file_dokumen));
            }
        } catch (\Exception $e) {
            return redirect()->route('informasi.event.index')->with('error', 'Event gagal dihapus!');
        }

        return redirect()->route('informasi.event.index')->with('success', 'Event sukses dihapus!');
    }
}
