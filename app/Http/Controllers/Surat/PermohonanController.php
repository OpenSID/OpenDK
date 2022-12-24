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
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Surat;

use App\Models\Surat;
use Yajra\DataTables\DataTables;
use App\Enums\LogVerifikasiSurat;
use App\Enums\StatusVerifikasiSurat;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PermohonanController extends Controller
{
    public function index()
    {
        $page_title       = 'Permohonan Surat';
        $page_description = 'Daftar Permohonan Surat';

        return view('surat.permohonan.index', compact('page_title', 'page_description'));
    }

    public function getData()
    {
        return DataTables::of(Surat::permohonan())
            ->addColumn('aksi', function ($row) {
                $data['show_url']     = route('surat.permohonan.show', $row->id);
                $data['download_url'] = route('surat.permohonan.download', $row->id);

                return view('forms.aksi', $data);
            })
            ->editColumn('nama', function ($row) {
                return "Surat {$row->nama}";
            })
            ->editColumn('log_verifikasi', function ($row) {
                if ($row->log_verifikasi == LogVerifikasiSurat::ProsesTTE) {
                    return "<span class='label label-warning'>Menunggu Ditandatangani {$this->settings['sebutan_camat']}</span>";
                } elseif ($row->log_verifikasi == LogVerifikasiSurat::Camat) {
                    return "<span class='label label-warning'>Menunggu Verifikasi {$this->settings['sebutan_camat']}</span>";
                } elseif ($row->log_verifikasi == LogVerifikasiSurat::Sekretaris) {
                    return "<span class='label label-warning'>Menunggu Verifikasi {$this->settings['sebutan_sekretaris']}</span>";
                } else {
                    return "<span class='label label-warning'>Menunggu Verifikasi Operator</span>";
                }
            })
            ->editColumn('tanggal', function ($row) {
                return format_date($row->tanggal);
            })
            ->rawColumns(['aksi', 'nama', 'log_verifikasi'])->make();
    }

    public function show($id)
    {
        $surat            = Surat::findOrFail($id);
        $page_title       = 'Detail Surat';
        $page_description = "Detail Data Surat: {$surat->nama}";

        return view('surat.permohonan.show', compact('page_title', 'page_description', 'surat'));
    }

    public function download($id)
    {
        try {
            $surat = Surat::findOrFail($id);

            return Storage::download('public/surat/' . $surat->file);
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Dokumen tidak ditemukan');
        }
    }

    public function setujui($id)
    {
        try {
            $surat        = Surat::findOrFail($id);
            $log_sekarang = $surat->log_verifikasi;

            if ($log_sekarang == LogVerifikasiSurat::Operator) {
                $log_verifikasi = $surat->verifikasi_sekretaris == StatusVerifikasiSurat::MenungguVerifikasi ? 
                    LogVerifikasiSurat::Sekretaris : LogVerifikasiSurat::Camat;
            } elseif ($log_sekarang == LogVerifikasiSurat::Sekretaris) {
                $log_verifikasi = LogVerifikasiSurat::Camat;
            } else {
                $log_verifikasi = LogVerifikasiSurat::ProsesTTE;
            }

            $surat->update(['log_verifikasi' => $log_verifikasi]);
        } catch (\Exception $e) {
            report($e);
        }
        return response()->json();
    }
}
