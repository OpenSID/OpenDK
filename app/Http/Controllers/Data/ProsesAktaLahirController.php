<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\ProsesAktaLahir;
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

class ProsesAktaLahirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Proses Akta Lahir';
        $page_description = 'Data Proses Pembuatan Akta Lahir';
        return view('data.proses_aktalahir.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataProsesAktaLahir()
    {
        return DataTables::of(DB::table('das_proses_akta_lahir')->join('das_penduduk', 'das_proses_akta_lahir.penduduk_id', '=', 'das_penduduk.id')
            ->select('das_penduduk.nama as nama_penduduk, das_proses_akta_lahir.alamat, das_proses_akta_lahir.tanggal_pengajuan, das_proses_akta_lahir.tanggal_selesai, das_proses_akta_lahir.status, das_proses_akta_lahir.catatan')
            ->get())
            ->addColumn('action', function ($row) {
                $edit_url   = route('data.proses-aktalahir.edit', $row->id);
                $delete_url = route('data.proses-aktalahir.destroy', $row->id);

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
        $page_description = 'Tambah Proses Akta Lahir Baru';

        return view('data.proses_aktalahir.create', compact('page_title', 'page_description'));
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

            ProsesAktaLahir::create($request->all());

            return redirect()->route('data.proses-aktalahir.index')->with('success', 'Data Proses Akta Lahir Baru berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data Proses Akta Lahir gagal disimpan!');
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
        $akta             = ProsesAktaLahir::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Proses Akta Lahir : ' . $akta->penduduk->nama;

        return view('data.proses_aktalahir.edit', compact('page_title', 'page_description', 'akta'));
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

            ProsesAktaLahir::find($id)->update($request->all());

            return redirect()->route('data.proses-aktalahir.index')->with('success', 'Data Proses Akta Lahir Baru berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data Proses Akta Lahir gagal disimpan!');
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
            ProsesAktaLahir::findOrFail($id)->delete();

            return redirect()->route('data.proses-aktalahir.index')->with('success', 'Proses Akta Lahir sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('data.proses-aktalahir.index')->with('error', 'Proses Akta Lahir gagal dihapus!');
        }
    }
}
