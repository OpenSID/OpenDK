<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\ProsesEKTP;
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

class ProsesEKTPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Proses e-KTP';
        $page_description = 'Data Proses e-KTP';

        return view('data.proses_ektp.index', compact('page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Proses e-KTP Baru';

        return view('data.proses_ektp.create', compact('page_title', 'page_description'));
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
                'tanggal_pengajuan' => 'required',
                'status'            => 'required',
            ]);

            ProsesEKTP::create($request->all());

            return redirect()->route('data.proses-ektp.index')->with('success', 'Proses e-KTP berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Proses e-KTP gagal disimpan!');
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
        $ektp             = ProsesEKTP::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Proses e-KTP : ' . $ektp->penduduk->nama;

        return view('data.proses_ektp.edit', compact('page_title', 'page_description', 'ektp'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $ektp = ProsesEKTP::findOrFail($id);
        $ektp->fill($request->all());
        try {
            request()->validate([
                'penduduk_id'       => 'required',
                'alamat'            => 'required',
                'tanggal_pengajuan' => 'required',
                'status'            => 'required',
            ]);

            $ektp->update();

            return redirect()->route('data.proses-ektp.index')->with('success', 'Proses e-KTP berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Proses e-KTP gagal disimpan!');
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
            ProsesEKTP::findOrFail($id)->delete();

            return redirect()->route('data.proses-ektp.index')->with('success', 'Proses e-KTP sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('data.proses-ektp.index')->with('error', 'Proses e-KTP gagal dihapus!');
        }
    }

    public function getDataProsesKTP()
    {
        return DataTables::of(DB::table('das_proses_ektp')
            ->join('das_penduduk', 'das_proses_ektp.penduduk_id', '=', 'das_penduduk.id')
            ->select([
                'das_proses_ektp.id',
                'das_penduduk.nama as nama',
                'das_proses_ektp.nik',
                'das_proses_ektp.alamat as alamat',
                'das_proses_ektp.tanggal_pengajuan',
                'das_proses_ektp.tanggal_selesai',
                'das_proses_ektp.status'
            ]))
            ->addColumn('action', function ($row) {
                $edit_url   = route('data.proses-ektp.edit', $row->id);
                $delete_url = route('data.proses-ektp.destroy', $row->id);

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
}
