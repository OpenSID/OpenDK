<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\KategoriKomplain;
use App\Models\Komplain;
use App\Models\Penduduk;
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
use function strtolower;
use function ucfirst;
use function view;

class AdminKomplainController extends Controller
{
    public function index()
    {
        $page_title       = 'Admin Keluhan';
        $page_description = 'Data Admin Keluhan';
        return view('sistem_komplain.admin_komplain.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataKomplain()
    {
        return DataTables::of(Komplain::with(['kategori_komplain']))
            ->addColumn('actions', function ($row) {
                $edit_url   = route('admin-komplain.edit', $row->id);
                $delete_url = route('admin-komplain.destroy', $row->id);
                if ($row->status == 'REVIEW' || $row->status == 'DITOLAK' | $row->status == 'BELUM') {
                    $agree_url         = route('admin-komplain.setuju', $row->id);
                    $data['agree_url'] = $agree_url;
                }

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->editColumn('kategori', function ($row) {
                return $row->kategori_komplain->nama;
            })
            ->editColumn('status', function ($row) {
                $status = '';
                if ($row->status == 'REVIEW') {
                    $status = '<span class="label label-default">' . $row->status . '</span>';
                }
                if ($row->status == 'DITOLAK') {
                    $status = '<span class="label label-danger">' . $row->status . '</span>';
                }
                if ($row->status == 'BELUM') {
                    $status = '<span class="label label-primary">' . $row->status . '</span>';
                }
                if ($row->status == 'SELESAI') {
                    $status = '<span class="label label-success">' . $row->status . '</span>';
                }
                if ($row->status == 'PROSES') {
                    $status = '<span class="label label-warning">' . $row->status . '</span>';
                }
                return $status;
            })
            ->rawColumns(['actions', 'status'])->make();
    }

    public function disetujui(Request $request, $id)
    {
        try {
            request()->validate([
                'status' => 'required',
            ]);

            Komplain::find($id)->update($request->all());

            return redirect()->route('admin-komplain.index')->with('success', 'Status Komplain berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Status Komplain gagal disimpan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $komplain         = Komplain::find($id);
        $page_title       = 'Edit Komplain';
        $page_description = 'Komplain ' . $komplain->komplain_id;
        return view('sistem_komplain.admin_komplain.edit', compact('page_title', 'page_description', 'komplain'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // Save Request
        try {
            $komplain = Komplain::findOrFail($id);
            $komplain->fill($request->all());
            $komplain->nama = Penduduk::where('nik', $komplain->nik)->first()->nama;

            request()->validate([
                'nik'      => 'required|numeric',
                'judul'    => 'required',
                'kategori' => 'required',
                'laporan'  => 'required',
            ]);

            // Save if lampiran available
            if ($request->hasFile('lampiran1')) {
                $lampiran1 = $request->file('lampiran1');
                $fileName1 = $lampiran1->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran1')->move($path, $fileName1);
                $komplain->lampiran1 = $path . $fileName1;
            }

            if ($request->hasFile('lampiran2')) {
                $lampiran2 = $request->file('lampiran2');
                $fileName2 = $lampiran2->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran2')->move($path, $fileName2);
                $komplain->lampiran2 = $path . $fileName2;
            }

            if ($request->hasFile('lampiran3')) {
                $lampiran3 = $request->file('lampiran3');
                $fileName3 = $lampiran3->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran3')->move($path, $fileName3);
                $komplain->lampiran3 = $path . $fileName3;
            }

            if ($request->hasFile('lampiran4')) {
                $lampiran4 = $request->file('lampiran4');
                $fileName4 = $lampiran4->getClientOriginalName();
                $path      = "storage/komplain/" . $komplain->komplain_id . '/';
                $request->file('lampiran3')->move($path, $fileName4);
                $komplain->lampiran4 = $path . $fileName4;
            }

            $komplain->save();
            return redirect()->route('admin-komplain.index')->with('success', 'Komplain berhasil dikirim!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Komplain gagal dikirim!');
        }
    }

    public function statistik()
    {
        $page_title       = 'Statistik Keluhan';
        $page_description = 'Data Statistik Keluhan Masyarakat';
        $chart_kategori   = $this->getChartKategori();
        $chart_status     = $this->getChartStatus();
        $chart_desa       = $this->getChartDesa();
        return view('sistem_komplain.admin_komplain.statistik', compact('page_title', 'page_description', 'chart_kategori', 'chart_status', 'chart_desa'));
    }

    protected function getChartKategori()
    {
        $data_chart = [];
        $kategori   = KategoriKomplain::all();
        foreach ($kategori as $value) {
            $query_total  = DB::table('das_komplain')
                ->where('kategori', '=', $value->id);
            $total        = $query_total->count();
            $data_chart[] = ['kategori' => $value->nama, 'value' => $total];
        }

        return $data_chart;
    }

    protected function getChartStatus()
    {
        $data_chart = [];
        $status     = ['REVIEW', 'DITOLAK', 'BELUM', 'PROSES', 'SELESAI'];
        $colors     = ['REVIEW' => '#f4f4f4', 'DITOLAK' => '#c9302c', 'BELUM' => '#286090', 'PROSES' => '#ec971f', 'SELESAI' => '#00a65a'];
        foreach ($status as $key => $value) {
            $query_total  = DB::table('das_komplain')
                ->where('status', '=', $value);
            $total        = $query_total->count();
            $data_chart[] = ['status' => ucfirst(strtolower($value)), 'value' => $total, 'color' => $colors[$value]];
        }

        return $data_chart;
    }

    protected function getChartDesa()
    {
        $data_chart = [];
        $desa       = DataDesa::all();
        foreach ($desa as $value) {
            $query_total  = DB::table('das_komplain')
                ->join('das_penduduk', 'das_komplain.nik', '=', 'das_penduduk.nik')
            ->where('das_penduduk.desa_id', $value->desa_id);
            $total        = $query_total->count();
            $data_chart[] = ['desa' => $value->nama, 'value' => $total];
        }

        return $data_chart;
    }
}
