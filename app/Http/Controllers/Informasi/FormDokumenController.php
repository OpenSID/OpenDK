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

use App\Enums\TipeWaktuFormDokumen;
use App\Enums\StatusFormDokumen;
use App\Enums\KonversiHariFormDokumen;
use App\Models\FormDokumen;
use App\Models\JenisDokumen;
use Yajra\DataTables\DataTables;
use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\DokumenRequest;
use Illuminate\Http\Request;

class FormDokumenController extends Controller
{
    use HandlesFileUpload;

    public function index()
    {
        $page_title = 'Dokumen';
        $page_description = 'Daftar Dokumen';
        $jenis_dokumen  = JenisDokumen::all();

        return view('informasi.form_dokumen.index', compact('page_title', 'page_description', 'jenis_dokumen'));
    }

    public function getDataDokumen(Request $request)
    {
        $query = FormDokumen::query();

        if ($request->filled('bulan')) {
            $query->whereMonth('published_at', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('published_at', $request->tahun);
        }
        if ($request->filled('jenis_dokumen_id')) {
            $query->where('jenis_dokumen_id', $request->jenis_dokumen_id);
        }

        return DataTables::of($query)
            ->addColumn('aksi', function ($row) {
                if (! auth()->guest()) {
                    $data['edit_url'] = route('informasi.form-dokumen.edit', $row->id);
                    $data['delete_url'] = route('informasi.form-dokumen.destroy', $row->id);
                }

                $data['download_url'] = route('informasi.form-dokumen.download', $row->id);
                

                return view('forms.aksi', $data);
            })
            ->editColumn('description', function ($row) {
                return $row->description ?? '-';
            })
            ->editColumn('jenis_dokumen', function ($row) {
                return $row->jenis_dokumen ?? '-';
            })
            ->editColumn('published_at', function ($row) {
                return $row->published_at ?? $row->created_at;
            })
            ->editColumn('expired_at', function ($row) {
                return $row->expired_at ?? 'Selamanya';
            })
            ->rawColumns(['aksi']) // biar HTML dari view 'aksi' nggak di-escape
            ->make(true);
    }

    public function create()
    {
        $page_title = 'Dokumen';
        $page_description = 'Tambah Dokumen';
        $status_options = StatusFormDokumen::options();
        $tipe_waktu_options = TipeWaktuFormDokumen::options();

        return view('informasi.form_dokumen.create', compact('page_title', 'page_description', 'status_options', 'tipe_waktu_options'));
    }

    public function store(DokumenRequest $request)
    {
        try {
            $input = $request->input();

            $jenis = JenisDokumen::find($input['jenis_dokumen_id']);
            $input['jenis_dokumen'] = $jenis->nama;

            $isPublished = $input['status'] == StatusFormDokumen::Terbit;
            $input['is_published'] = $isPublished;


            if ($isPublished) {
                $publishedAt = now();
                $input['published_at'] = $publishedAt;

                if($input['retention_days'] > 0){
                    $retentionDays = intval($input['retention_days']);
                    $input['expired_at'] = $publishedAt->copy()->addDays($retentionDays);
                }else{
                    $input['expired_at'] = null;
                }
            } else {
                $input['published_at'] = null;
                $input['expired_at'] = null;
            }

            $this->handleFileUpload($request, $input, 'file_dokumen', 'form_dokumen');

            FormDokumen::create($input);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Dokumen gagal disimpan!');
        }

        return redirect()->route('informasi.form-dokumen.index')->with('success', 'Dokumen berhasil ditambah!');
    }

    public function edit(FormDokumen $dokumen)
    {
        $page_title = 'Dokumen';
        $page_description = 'Ubah Dokumen '.$dokumen->nama_dokumen;

        $jumlah_waktu = 0;
        $tipe_waktu = TipeWaktuFormDokumen::Hari;
        $status = StatusFormDokumen::Terbit;

        $status_options = StatusFormDokumen::options();
        $tipe_waktu_options = TipeWaktuFormDokumen::options();

        if ($dokumen->retention_days > 0) {
            if ($dokumen->retention_days >= KonversiHariFormDokumen::Tahun ) {
                $sisa_hari = $dokumen->retention_days % KonversiHariFormDokumen::Tahun;
                if($sisa_hari == 0){
                    $jumlah_waktu = $dokumen->retention_days / KonversiHariFormDokumen::Tahun;
                    $tipe_waktu = TipeWaktuFormDokumen::Tahun;
                }else{
                    $jumlah_waktu = floor($dokumen->retention_days / KonversiHariFormDokumen::Bulan);
                    $tipe_waktu = TipeWaktuFormDokumen::Bulan;
                }
            } elseif ($dokumen->retention_days > (KonversiHariFormDokumen::Bulan + 1)) {
                $jumlah_waktu = $dokumen->retention_days / KonversiHariFormDokumen::Bulan;
                $tipe_waktu = TipeWaktuFormDokumen::Bulan;
            } else {
                $jumlah_waktu = $dokumen->retention_days;
                $tipe_waktu = TipeWaktuFormDokumen::Hari;
            }
        }
        if($dokumen->is_published){
            $status = StatusFormDokumen::Terbit;
        }else{
            $status = StatusFormDokumen::Draft;
        }

        return view('informasi.form_dokumen.edit', compact('page_title', 'page_description', 'dokumen', 'jumlah_waktu', 'tipe_waktu', 'status', 'status_options', 'tipe_waktu_options'));
    }

    public function update(DokumenRequest $request, FormDokumen $dokumen)
    {
        try {
            $input = $request->input();

            $jenis = JenisDokumen::find($input['jenis_dokumen_id']);
            $input['jenis_dokumen'] = $jenis->nama;

            $isPublished = $input['status'] == StatusFormDokumen::Terbit;
            $input['is_published'] = $isPublished;


            if ($isPublished) {
                $publishedAt = now();
                $input['published_at'] = $publishedAt;

                if($input['retention_days'] > 0){
                    $retentionDays = intval($input['retention_days']);
                    $input['expired_at'] = $publishedAt->copy()->addDays($retentionDays);
                }else{
                    $input['expired_at'] = null;
                }
            } else {
                $input['published_at'] = null;
                $input['expired_at'] = null;
            }

            $this->handleFileUpload($request, $input, 'file_dokumen', 'form_dokumen');

            $dokumen->update($input);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Dokumen gagal diubah!');
        }

        return redirect()->route('informasi.form-dokumen.index')->with('success', 'Dokumen berhasil diubah!');
    }

    public function destroy(FormDokumen $dokumen)
    {
        try {
            $dokumen->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('informasi.form-dokumen.index')->with('error', 'Dokumen gagal dihapus!');
        }

        return redirect()->route('informasi.form-dokumen.index')->with('success', 'Dokumen berhasil dihapus!');
    }

    public function download(FormDokumen $dokumen)
    {
        try {
            return response()->download($dokumen->file_dokumen);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Dokumen tidak ditemukan');
        }
    }
}
