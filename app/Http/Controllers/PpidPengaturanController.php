<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers;

use App\Models\PpidPengaturan;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PpidPengaturanController extends Controller
{
    use HandlesFileUpload;

    /**
     * Display the form for editing PPID settings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get or create pengaturan (single record settings)
        $pengaturan = PpidPengaturan::first();

        if (! $pengaturan) {
            $pengaturan = PpidPengaturan::create([
                'ppid_judul' => 'Layanan Informasi Publik Desa',
                'ppid_permohonan' => '1',
                'ppid_keberatan' => '1',
            ]);
        }

        $page_title = 'PPID';
        $page_description = 'Pengaturan PPID';

        return view('ppid.pengaturan.edit', compact('page_title', 'page_description', 'pengaturan'));
    }

    /**
     * Update the PPID settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $pengaturan = PpidPengaturan::findOrFail($id);

        // Validation rules
        $rules = [
            'ppid_judul' => 'nullable|string|max:255',
            'ppid_informasi' => 'nullable|string',
            'ppid_batas_pengajuan' => 'nullable|integer|min:1',
            'ppid_permohonan' => 'required|in:1,0',
            'ppid_keberatan' => 'required|in:1,0',
            'ppid_banner' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
        ];

        // Custom validation messages
        $messages = [
            'ppid_judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'ppid_batas_pengajuan.integer' => 'Batas pengajuan harus berupa angka.',
            'ppid_batas_pengajuan.min' => 'Batas pengajuan minimal 1 hari.',
            'ppid_permohonan.required' => 'Status permohonan wajib dipilih.',
            'ppid_permohonan.in' => 'Status permohonan tidak valid.',
            'ppid_keberatan.required' => 'Status keberatan wajib dipilih.',
            'ppid_keberatan.in' => 'Status keberatan tidak valid.',
            'ppid_banner.image' => 'Banner harus berupa gambar.',
            'ppid_banner.mimes' => 'Banner harus berformat jpg, jpeg, png, atau bmp.',
            'ppid_banner.max' => 'Ukuran banner maksimal 2MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $input = $request->all();

            // Handle file upload for banner
            $this->handleFileUpload($request, $input, 'ppid_banner', 'ppid');

            // Update pengaturan
            $pengaturan->update($input);

            return redirect()
                ->route('ppid.pengaturan.index')
                ->with('success', 'Pengaturan PPID berhasil disimpan!');
        } catch (\Exception $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Pengaturan PPID gagal disimpan!');
        }
    }
}
