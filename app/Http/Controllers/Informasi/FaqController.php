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
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('informasi.faq.index');
    }
    
    public function getDataFaq(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Faq::all())
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $data['show_web'] = route('faq');

                    if (! auth()->guest()) {
                        $data['edit_url']   = route('informasi.faq.edit', $row->id);
                        $data['delete_url'] = route('informasi.faq.destroy', $row->id);
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
        $page_title       = 'FAQ';
        $page_description = 'Tambah FAQ';

        return view('informasi.faq.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'question' => 'required',
            'answer'   => 'required',
        ]);

        try {
            Faq::create($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'FAQ gagal ditambah!');
        }

        return redirect()->route('informasi.faq.index')->with('success', 'FAQ berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $faq              = Faq::findOrFail($id);
        $page_title       = 'FAQ';
        $page_description = 'Ubah FAQ : ' . $faq->question;

        return view('informasi.faq.edit', compact('page_title', 'page_description', 'faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */

    public function update(Request $request, $id)
    {
        request()->validate([
            'question' => 'required',
            'answer'   => 'required',
        ]);

        try {
            Faq::findOrFail($id)->update($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'FAQ gagal diubah!');
        }

        return redirect()->route('informasi.faq.index')->with('success', 'FAQ berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            Faq::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('informasi.faq.index')->with('error', 'FAQ gagal dihapus!');
        }

        return redirect()->route('informasi.faq.index')->with('success', 'FAQ berhasil dihapus!');
    }
}
