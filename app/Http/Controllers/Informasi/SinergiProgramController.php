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

use Illuminate\Http\Request;
use App\Models\SinergiProgram;
use Yajra\DataTables\DataTables;
use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\SinergiProgramRequest;

class SinergiProgramController extends Controller
{
    use HandlesFileUpload;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('informasi.sinergi_program.index');
    }

    public function getDataSinergiProgram(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(SinergiProgram::all())
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $data['show_web'] = $row->url;

                    if (! auth()->guest()) {
                        $data['edit_url'] = route('informasi.sinergi-program.edit', $row->id);
                        $data['delete_url'] = route('informasi.sinergi-program.destroy', $row->id);
                        $data['naik'] = route('informasi.sinergi-program.urut', [$row->id, -1]);
                        $data['turun'] = route('informasi.sinergi-program.urut', [$row->id, 1]);
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
                ->editColumn('gambar', function ($row) {
                    return '<img src="' . asset($row->gambar) . '" style="max-width:100px; max-height:60px;"/>';
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
        $sinergi = null;
        $page_title = 'Sinergi Program';
        $page_description = 'Tambah Sinergi Program';

        return view('informasi.sinergi_program.create', compact('page_title', 'page_description', 'sinergi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(SinergiProgramRequest $request)
    {
        try {
            $input = $request->validated();
            $this->handleFileUpload($request, $input, 'gambar', 'sinergi/');
            SinergiProgram::create($input);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Sinergi Program gagal disimpan!');
        }

        return redirect()->route('informasi.sinergi-program.index')->with('success', 'Sinergi Program berhasil disimpan!');
    }

    public function edit(SinergiProgram $sinergi)
    {
        $page_title = 'Sinergi Program';
        $page_description = 'Ubah Sinergi Program : ' . $sinergi->nama;

        return view('informasi.sinergi_program.edit', compact('page_title', 'page_description', 'sinergi'));
    }

    public function update(SinergiProgram $sinergi, SinergiProgramRequest $request)
    {
        try {
            $input = $request->validated();
            $this->handleFileUpload($request, $input, 'gambar', 'sinergi/');

            $sinergi->update($input);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Sinergi Program gagal diubah!');
        }

        return redirect()->route('informasi.sinergi-program.index')->with('success', 'Sinergi Program berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(SinergiProgram $sinergi)
    {
        try {
            $sinergi->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('informasi.sinergi-program.index')->with('error', 'Sinergi Program gagal dihapus!');
        }

        return redirect()->route('informasi.sinergi-program.index')->with('success', 'Sinergi Program berhasil dihapus!');
    }

    public function urut(SinergiProgram $sinergi, $urutan)
    {
        try {
            if ($urutan == -1 && SinergiProgram::min('urutan') == $sinergi->urutan) {
                return redirect()->route('informasi.sinergi-program.index')->with('error', 'Urutan Sinergi Program sudah berada diurutan pertama!');
            } elseif ($urutan == 1 && SinergiProgram::max('urutan') == $sinergi->urutan) {
                return redirect()->route('informasi.sinergi-program.index')->with('error', 'Urutan Sinergi Program sudah berada diurutan terakhir!');
            } else {
                $perubahan = $sinergi->urutan + $urutan;
                SinergiProgram::where('urutan', $perubahan)->update(['urutan' => $sinergi->urutan]);
                $sinergi->update(['urutan' => $perubahan]);
            }
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('informasi.sinergi-program.index')->with('error', 'Urutan Sinergi Program gagal diubah!');
        }

        return redirect()->route('informasi.sinergi-program.index')->with('success', 'Urutan Sinergi Program berhasil diubah!');
    }
}
