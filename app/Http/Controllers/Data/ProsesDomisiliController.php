<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\ProsesDomisili;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function redirect;
use function request;
use function route;
use function view;

class ProsesDomisiliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Proses Surat Pindah Alamat';
        $page_description = 'Data Proses Pembuatan Surat Pindah Alamat';
        return view('data.proses_domisili.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataProsesDomisili()
    {
        return DataTables::of(DB::table('das_proses_domisili')
            ->join('das_penduduk', 'das_proses_domisili.penduduk_id', '=', 'das_penduduk.id')
            ->select([
                'das_proses_domisili.id',
                'das_penduduk.nama as nama_penduduk',
                'das_proses_domisili.alamat',
                'das_proses_domisili.tanggal_pengajuan',
                'das_proses_domisili.tanggal_selesai',
                'das_proses_domisili.status',
                'das_proses_domisili.catatan',
            ])
            ->get())
            ->addColumn('action', function ($row) {
                $edit_url   = route('data.proses-domisili.edit', $row->id);
                $delete_url = route('data.proses-domisili.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->editColumn('status', function ($row) {
                $status = '';
                if ($row->status == 'PENGAJUAN') {
                    $status = '<span class="badge bg-info">PENGAJUAN</span>';
                } elseif ($row->status == 'PROSES') {
                    $status = '<span class="badge bg-yellow-active">PROSES</span>';
                } elseif ($row->status == 'SELESAI') {
                    $status = '<span class="badge bg-green">SELESAI</span>';
                }
                return $status;
            })
            ->rawColumns(['status', 'action'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Proses Surat Pindah Alamat';

        return view('data.proses_domisili.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            request()->validate([
                'penduduk_id'       => 'required',
                'alamat'            => 'required',
                'tanggal_pengajuan' => 'required|date',
                'status'            => 'required',
            ]);

            ProsesDomisili::create($request->all());

            return redirect()->route('data.proses-domisili.index')->with('success', 'Data Proses Surat Domisili berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data Proses Proses Surat Domisili gagal disimpan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $domisili         = ProsesDomisili::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Proses Surat Pindah Alamat : ' . $domisili->penduduk->nama;

        return view('data.proses_domisili.edit', compact('page_title', 'page_description', 'domisili'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            request()->validate([
                'penduduk_id'       => 'required',
                'alamat'            => 'required',
                'tanggal_pengajuan' => 'required|date',
                'status'            => 'required',
            ]);

            ProsesDomisili::find($id)->update($request->all());

            return redirect()->route('data.proses-domisili.index')->with('success', 'Data Proses Surat Domisili berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data Proses Proses Surat Domisili gagal disimpan!');
        }
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
            ProsesDomisili::findOrFail($id)->delete();

            return redirect()->route('data.proses-domisili.index')->with('success', 'Proses Surat Domisili berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->route('data.proses-domisili.index')->with('error', 'Proses Surat Domisili gagal dihapus!');
        }
    }
}
