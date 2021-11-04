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

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Flysystem\Exception;
use Yajra\DataTables\DataTables;

class SlideController extends Controller
{
    public function index()
    {
        $page_title       = 'Slide';
        $page_description = 'Daftar Slide';

        return view('setting.slide.index', compact('page_title', 'page_description'));
    }

    /**
    * Get datatable
    */
    public function getData()
    {
        return DataTables::of(Slide::all())
            ->addColumn('action', function ($row) {
                // $data['show_url']   = route('setting.slide.show', $row->id); //TODO : Tambahkan View
                $data['edit_url']   = route('setting.slide.edit', $row->id);
                $data['delete_url'] = route('setting.slide.destroy', $row->id);

                return view('forms.action', $data);
            })
            ->editColumn('judul', function ($row) {
                return $row->judul;
                return $row->deskripsi;
            })->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Slide';
        $page_description = 'Tambah Slide';

        return view('setting.slide.create', compact('page_title', 'page_description'));
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

        try {
            $slide = new Slide($request->input());

            if ($request->hasFile('gambar')) {
                $file     = $request->file('gambar');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/slide/";
                $request->file('gambar')->move($path, $fileName);
                $slide->gambar = $path . $fileName;
            }
            $slide->save();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Slide gagal ditambah!');
        }

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
        //TODO : Tambahkan View

        $slide      = Slide::findOrFail($id);
        $page_title = 'Detail Slide :' . $slide->judul;

        return view('setting.slide.show', compact('page_title', 'page_description', 'slide'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $slide            = Slide::findOrFail($id);
        $page_title       = 'Slide';
        $page_description = 'Ubah Slide : ' . $slide->judul;

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
        request()->validate([
            'judul'     => 'required',
            'deskripsi' => 'required|max:100',
            'gambar'    => 'file|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);

        try {
            $slide = Slide::findOrFail($id);
            $slide->fill($request->all());

            if ($request->hasFile('gambar')) {
                $file     = $request->file('gambar');
                $fileName = $file->getClientOriginalName();
                $path     = "storage/slide/";
                $request->file('gambar')->move($path, $fileName);
                $slide->gambar = $path . $fileName;
            }
            $slide->save();
        } catch (Exception $e) {
            return back()->with('error', 'Data Slide gagal disimpan!' . $e->getMessage());
        }

        return redirect()->route('setting.slide.index')->with('success', 'Data Slide berhasil disimpan!');
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
            Slide::findOrFail($id)->delete();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Slide gagal dihapus!');
        }

        return redirect()->route('setting.slide.index')->with('success', 'Slide berhasil dihapus!');
    }
}
