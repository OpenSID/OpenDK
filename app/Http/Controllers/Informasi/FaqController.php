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
use App\Models\Faq;
use function back;
use function compact;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function redirect;
use function request;
use function view;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'FAQ';
        $page_description = 'Frequently Ask and Question';

        $faqs = Faq::latest()->paginate(10);
        return view('informasi.faq.index', compact('page_title', 'page_description', 'faqs'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Tambah FAQ';
        $page_description = '';

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
        Faq::create($request->all());

        return redirect()->route('informasi.faq.index')->with('success', 'FAQ berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $faq              = Faq::find($id);
        $page_title       = 'Ubah FAQ';
        $page_description = $faq->question;

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
        try {
            request()->validate([
                'question' => 'required',
                'answer'   => 'required',
            ]);

            Faq::find($id)->update($request->all());

            return redirect()->route('informasi.faq.index')->with('success', 'Update FAQ sukses!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Update FAQ gagal!');
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
        try {
            Faq::findOrFail($id)->delete();

            return redirect()->route('informasi.faq.index')->with('success', 'FAQ sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('informasi.faq.index')->with('error', 'FAQ gagal dihapus!');
        }
    }
}
