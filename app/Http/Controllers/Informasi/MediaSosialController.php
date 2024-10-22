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

namespace App\Http\Controllers\Informasi;

use App\Models\MediaSosial;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\MediaSosialRequest;

class MediaSosialController extends Controller
{
    use HandlesFileUpload;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('informasi.media_sosial.index');
    }

    public function getDataMediaSosial(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(MediaSosial::all())
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $data['show_web'] = $row->url;

                    if (! auth()->guest()) {
                        $data['edit_url'] = route('informasi.media-sosial.edit', $row->id);
                        $data['delete_url'] = route('informasi.media-sosial.destroy', $row->id);
                    }

                    return view('forms.aksi', $data);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 0) {
                        return '<span class="label label-danger">Tidak Aktif</span>';
                    } else {
                        return '<span class="label label-success">Aktif</span>';
                    }
                })
                ->rawColumns(['status'])
                ->escapeColumns([])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $medsos = null;
        $page_title = 'Media Sosial';
        $page_description = 'Tambah Media Sosial';

        return view('informasi.media_sosial.create', compact('page_title', 'page_description', 'medsos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(MediaSosialRequest $request)
    {
        try {
            $input = $request->validated();
            $this->handleFileUpload($request, $input, 'logo', 'medsos');

            MediaSosial::create($input);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Media Sosial gagal disimpan!');
        }

        return redirect()->route('informasi.media-sosial.index')->with('success', 'Media Sosial berhasil disimpan!');
    }

    public function edit(MediaSosial $medsos)
    {
        $page_title = 'Media Sosial';
        $page_description = 'Ubah Media Sosial : '.$medsos->nama;

        return view('informasi.media_sosial.edit', compact('page_title', 'page_description', 'medsos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(MediaSosialRequest $request, $id)
    {
        $medsos = MediaSosial::findOrFail($id);

        try {
            $input = $request->validated();
            $this->handleFileUpload($request, $input, 'logo', 'medsos');

            $medsos->update($input);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Media Sosial gagal diubah!');
        }

        return redirect()->route('informasi.media-sosial.index')->with('success', 'Media Sosial berhasil diubah!');
    }

    public function destroy(MediaSosial $medsos)
    {
        try {
            $medsos->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('informasi.media-sosial.index')->with('error', 'Media Sosial gagal dihapus!');
        }

        return redirect()->route('informasi.media-sosial.index')->with('success', 'Media Sosial berhasil dihapus!');
    }
}
