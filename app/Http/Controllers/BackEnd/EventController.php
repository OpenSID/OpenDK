<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\BackEnd;

use App\Models\Event;
use Illuminate\Support\Carbon;
use App\Traits\HandlesFileUpload;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\BackEndController;

class EventController extends BackEndController
{
    use HandlesFileUpload;

    public function index()
    {
        $page_title = 'Event';
        $page_description = 'Daftar Event';

        return view('backend.event.index', compact('page_title', 'page_description'));
    }

    public function datatables()
    {
        return DataTables::of(Event::query())
            ->editColumn('start', function ($row) {
                return Carbon::parse($row->start)->format('d-m-Y H:i');
            })
            ->editColumn('end', function ($row) {
                return Carbon::parse($row->end)->format('d-m-Y H:i');
            })
            ->addColumn('aksi', function ($row) {
                $data = [];

                if ($row->status == 'OPEN') {
                    $data['show_url'] = route('event.detail', $row->slug);

                    if (! auth()->guest()) {
                        $data['edit_url'] = route('informasi.event.edit', $row->id);
                        $data['delete_url'] = route('informasi.event.destroy', $row->id);
                    }
                }
                
                if ($row->status == 'CLOSED' && $row->attachment != null) {
                    $data['download_url'] = route('informasi.event.download', $row->id);
                }

                return view('forms.aksi', $data);
            })
            ->make();
    }

    public function create()
    {
        $page_title = 'Event';
        $page_description = 'Tambah Event';

        return view('backend.event.create', compact('page_title', 'page_description'));
    }

    public function store(EventRequest $request)
    {
        try {
            $waktu = explode('-', $request->waktu);

            $input = $request->input();
            $input['start'] = date('Y-m-d H:i', strtotime($waktu[0]));
            $input['end'] = date('Y-m-d H:i', strtotime($waktu[1]));
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
        if ($event->status == 'CLOSED') {
            return redirect()->route('informasi.event.index')->with('error', 'Event sudah ditutup! Tidak bisa diubah!');
        }

        $page_title = 'Event';
        $page_description = 'Ubah Event';

        return view('backend.event.edit', compact('page_title', 'page_description', 'event'));
    }

    public function update(EventRequest $request, Event $event)
    {
        try {
            $waktu = explode('-', $request->waktu);
            $input = $request->all();
            $this->handleFileUpload($request, $input, 'attachment', "event/{$event->id}");
            $input['end'] = date('Y-m-d H:i', strtotime($waktu[1]));
            $input['start'] = date('Y-m-d H:i', strtotime($waktu[0]));

            $event->update($input);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Data Event gagal disimpan!');
        }

        return redirect()->route('informasi.event.index')->with('success', 'Data Event berhasil disimpan!');
    }

    public function destroy(Event $event)
    {
        if ($event->status == 'CLOSED') {
            return redirect()->route('informasi.event.index')->with('error', 'Event sudah ditutup! Tidak bisa dihapus!');
        }

        try {
            $event->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('informasi.event.index')->with('error', 'Event Gagal dihapus!');
        }

        return redirect()->route('informasi.event.index')->with('success', 'Event Berhasil dihapus!');
    }

    public function download(Event $event)
    {
        return response()->download(base_path('public/'.$event->attachment));
    }
}
