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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\KategoriKomplain;
use App\Models\Komplain;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AdminKomplainController extends Controller
{
    public function index()
    {
        $page_title       = 'Komplain';
        $page_description = 'Daftar Komplain';

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
            ->addColumn('aksi', function ($row) {
                if ($row->status == 'REVIEW' || $row->status == 'DITOLAK' | $row->status == 'BELUM') {
                    $data['agree_url'] = route('admin-komplain.setuju', $row->id);
                }

                $data['edit_url']   = route('admin-komplain.edit', $row->id);
                $data['delete_url'] = route('admin-komplain.destroy', $row->id);

                return view('forms.aksi', $data);
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
            ->rawColumns(['aksi', 'status'])->make();
    }

    public function disetujui(Request $request, $id)
    {
        request()->validate([
            'status' => 'required',
        ]);

        try {
            Komplain::findOrFail($id)->update($request->all());
        } catch (Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Status Komplain gagal disimpan!');
        }

        return redirect()->route('admin-komplain.index')->with('success', 'Status Komplain berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $komplain         = Komplain::findOrFail($id);
        $page_title       = 'Komplain';
        $page_description = 'Ubah Komplain' . $komplain->komplain_id;

        return view('sistem_komplain.admin_komplain.edit', compact('page_title', 'page_description', 'komplain'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'nik'      => 'required|numeric',
            'judul'    => 'required',
            'kategori' => 'required',
            'laporan'  => 'required',
        ]);

        try {
            $komplain = Komplain::findOrFail($id);
            $komplain->fill($request->all());
            $komplain->nama = Penduduk::where('nik', $komplain->nik)->first()->nama;

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
        } catch (Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Komplain gagal dikirim!');
        }

        return redirect()->route('admin-komplain.index')->with('success', 'Komplain berhasil dikirim!');
    }

    public function statistik()
    {
        $page_title       = 'Statistik Komplain';
        $page_description = 'Data Statistik Komplain Masyarakat';
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
