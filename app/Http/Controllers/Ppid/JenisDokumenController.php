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

namespace App\Http\Controllers\Ppid;

use App\Models\PpidJenisDokumen;
use App\Models\PpidDokumen;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ppid\JenisDokumenRequest;
use Yajra\DataTables\DataTables;

class JenisDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = 'Jenis Dokumen PPID';
        $page_description = 'Daftar Jenis Dokumen PPID';

        return view('ppid.jenis-dokumen.index', compact('page_title', 'page_description'));
    }

    /**
     * Get data for DataTables.
     *
     * @return \Yajra\DataTables\DataTables
     */
    public function getDataJenisDokumen()
    {
        return DataTables::of(PpidJenisDokumen::query()->orderBy('urutan'))
            ->addColumn('aksi', function ($row) {
                $data['modal_form'] = $row->id;
                $data['delete_url'] = route('ppid.jenis-dokumen.destroy', $row->id);
                return view('forms.aksi', $data);
            })
            ->editColumn('status', function ($row) {
                return $row->status ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>';
            })
            ->rawColumns(['aksi', 'status'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('ppid.jenis-dokumen.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Ppid\JenisDokumenRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisDokumenRequest $request)
    {
        try {
            PpidJenisDokumen::create($request->validated());

            session()->flash('success', 'Jenis Dokumen berhasil ditambahkan!');

            return response()->json([
                'success' => true,
                'message' => session('success')
            ]);
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', 'Jenis Dokumen gagal ditambahkan!');

            return response()->json([
                'success' => false,
                'message' => session('error')
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PpidJenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit(PpidJenisDokumen $jenisDokumen)
    {
        return response()->json($jenisDokumen);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Ppid\JenisDokumenRequest  $request
     * @param  \App\Models\PpidJenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(JenisDokumenRequest $request, PpidJenisDokumen $jenisDokumen)
    {
        try {
            $jenisDokumen->update($request->validated());

            session()->flash('success', 'Jenis Dokumen berhasil diupdate!');

            return response()->json([
                'success' => true,
                'message' => session('success')
            ]);
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', 'Jenis Dokumen gagal diupdate!');

            return response()->json([
                'success' => false,
                'message' => session('error')
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PpidJenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PpidJenisDokumen $jenisDokumen)
    {
        try {
            $isUsed = PpidDokumen::query()
                ->where('id_jenis_dokumen', $jenisDokumen->id)
                ->exists();

            if ($isUsed) {
                return back()->with('error', 'Jenis Dokumen tidak bisa dihapus karena sedang digunakan pada Dokumen PPID.');
            }

            $jenisDokumen->delete();
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Jenis Dokumen gagal dihapus!');
        }

        return redirect()->route('ppid.jenis-dokumen.index')->with('success', 'Jenis Dokumen berhasil dihapus!');
    }
}
