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

namespace App\Http\Controllers\Data;

use App\Enums\JenisJabatan;
use App\Http\Controllers\Controller;
use App\Http\Requests\JabatanRequest;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $page_title = 'Data Jabatan';
        $page_description = 'Daftar Data Jabatan';

        if ($request->ajax()) {
            return DataTables::of(Jabatan::all())
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    if (! auth()->guest()) {
                        $data['edit_url'] = route('data.jabatan.edit', $row->id);
                        if ($row->jenis == JenisJabatan::JabatanLainnya) {
                            $data['delete_url'] = route('data.jabatan.destroy', $row->id);
                        }
                    }

                    return view('forms.aksi', $data);
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('data.jabatan.index', compact('page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Jabatan';
        $page_description = 'Tambah Jabatan';

        return view('data.jabatan.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(JabatanRequest $request)
    {
        try {
            Jabatan::create($request->all());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Jabatan gagal ditambah!');
        }

        return redirect()->route('data.jabatan.index')->with('success', 'Jabatan berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $page_title = 'Jabatan';
        $page_description = 'Ubah Jabatan : '.$jabatan->nama;

        return view('data.jabatan.edit', compact('page_title', 'page_description', 'jabatan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(JabatanRequest $request, $id)
    {
        try {
            Jabatan::findOrFail($id)->update($request->all());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Jabatan gagal diubah!');
        }

        return redirect()->route('data.jabatan.index')->with('success', 'Jabatan berhasil diubah!');
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
            Jabatan::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('data.jabatan.index')->with('error', 'Jabatan gagal dihapus!');
        }

        return redirect()->route('data.jabatan.index')->with('success', 'Jabatan berhasil dihapus!');
    }
}
