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

namespace App\Http\Controllers\FrontEnd;

use App\Models\PpidDokumen;
use App\Models\PpidJenisDokumen;
use App\Models\PpidPengaturan;
use App\Models\PpidPermohonan;
use App\Http\Requests\Ppid\PermohonanRequest;
use Illuminate\Http\Request;

// Controller standalone untuk PPID Public
// Tidak me-extend Controller OpenDK untuk menghindari error saat database belum lengkap
class PpidPublicController
{
    public function index()
    {
        $page_title = 'PPID';
        $page_description = 'Pejabat Pengelola Informasi dan Dokumentasi';

        try {
            $jenis_dokumen = PpidJenisDokumen::aktif()->urut()->get();
            $dokumen_terbaru = PpidDokumen::where('status', true)
                ->with('jenisDokumen')
                ->latest()
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            $jenis_dokumen = collect();
            $dokumen_terbaru = collect();
        }

        $pengaturan = $this->getPengaturan();

        return view('frontend.ppid.index', compact(
            'page_title',
            'page_description',
            'jenis_dokumen',
            'dokumen_terbaru',
            'pengaturan'
        ));
    }

    public function dokumen($id = null)
    {
        $page_title = 'Dokumen PPID';
        $page_description = 'Daftar Dokumen PPID';

        try {
            $jenis_dokumen = PpidJenisDokumen::aktif()->urut()->get();

            if ($id) {
                $jenis = PpidJenisDokumen::findOrFail($id);
                $dokumen = PpidDokumen::where('status', true)
                    ->where('id_jenis_dokumen', $id)
                    ->with('jenisDokumen')
                    ->latest()
                    ->paginate(12);
            } else {
                $jenis = null;
                $dokumen = PpidDokumen::where('status', true)
                    ->with('jenisDokumen')
                    ->latest()
                    ->paginate(12);
            }
        } catch (\Exception $e) {
            $jenis_dokumen = collect();
            $dokumen = null;
            $jenis = null;
        }

        $pengaturan = $this->getPengaturan();

        return view('frontend.ppid.dokumen', compact(
            'page_title',
            'page_description',
            'jenis_dokumen',
            'dokumen',
            'jenis',
            'pengaturan'
        ));
    }

    public function showDokumen($id)
    {
        $dokumen = PpidDokumen::with('jenisDokumen')->findOrFail($id);

        if (!$dokumen->status) {
            abort(404);
        }

        $page_title = $dokumen->judul;
        $page_description = 'Detail Dokumen PPID';

        $pengaturan = $this->getPengaturan();

        return view('frontend.ppid.show_dokumen', compact(
            'page_title',
            'page_description',
            'dokumen',
            'pengaturan'
        ));
    }

    public function downloadDokumen($id)
    {
        $dokumen = PpidDokumen::findOrFail($id);

        if (!$dokumen->status || !$dokumen->file) {
            abort(404);
        }

        $filePath = public_path('storage/ppid_dokumen/' . $dokumen->file);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($filePath, $dokumen->judul . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }

    public function permohonan()
    {
        $page_title = 'Permohonan Informasi';
        $page_description = 'Formulir Permohonan Informasi Publik';

        $pengaturan = $this->getPengaturan();

        return view('frontend.ppid.permohonan', compact(
            'page_title',
            'page_description',
            'pengaturan'
        ));
    }

    public function storePermohonan(PermohonanRequest $request)
    {
        try {
            $input = $request->validated();
            $input['status'] = 'MENUNGGU';

            PpidPermohonan::create($input);

            return redirect()->route('ppid.permohonan')
                ->with('success', 'Permohonan informasi berhasil dikirim! Permohonan Anda akan diproses dalam waktu maksimal 10 hari kerja sesuai dengan peraturan perundang-undangan.');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Permohonan gagal dikirim. Silakan coba lagi atau hubungi admin.');
        }
    }

    public function cekPermohonan(Request $request)
    {
        $page_title = 'Cek Status Permohonan';
        $page_description = 'Cek Status Permohonan Informasi Publik';

        $permohonan = null;

        if ($request->has('nomor_permohonan')) {
            $permohonan = PpidPermohonan::where('id', $request->nomor_permohonan)
                ->first();

            if (!$permohonan) {
                return back()->with('error', 'Permohonan tidak ditemukan!');
            }
        }

        $pengaturan = $this->getPengaturan();

        return view('frontend.ppid.cek_permohonan', compact(
            'page_title',
            'page_description',
            'permohonan',
            'pengaturan'
        ));
    }

    private function getPengaturan()
    {
        try {
            return [
                'nama_ppid' => PpidPengaturan::getValue('nama_ppid', 'PPID'),
                'alamat_ppid' => PpidPengaturan::getValue('alamat_ppid', ''),
                'nomor_telepon' => PpidPengaturan::getValue('nomor_telepon', ''),
                'email_ppid' => PpidPengaturan::getValue('email_ppid', ''),
                'jam_operasional' => PpidPengaturan::getValue('jam_operasional', ''),
                'nama_pejabat' => PpidPengaturan::getValue('nama_pejabat', ''),
                'nip_pejabat' => PpidPengaturan::getValue('nip_pejabat', ''),
                'jabatan_pejabat' => PpidPengaturan::getValue('jabatan_pejabat', ''),
            ];
        } catch (\Exception $e) {
            return [
                'nama_ppid' => 'PPID',
                'alamat_ppid' => '',
                'nomor_telepon' => '',
                'email_ppid' => '',
                'jam_operasional' => '',
                'nama_pejabat' => '',
                'nip_pejabat' => '',
                'jabatan_pejabat' => '',
            ];
        }
    }
}
