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

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegulasiRequest;
use App\Models\Regulasi;

class RegulasiController extends Controller
{
    public function index()
    {
        $page_title       = 'Regulasi';
        $page_description = 'Daftar Regulasi';
        $regulasi         = Regulasi::latest()->paginate(10); // TODO : Gunakan datatable

        return view('informasi.regulasi.index', compact('page_title', 'page_description', 'regulasi'));
    }

    public function create()
    {
        $page_title       = 'Regulasi';
        $page_description = 'Tambah Regulasi';

        return view('informasi.regulasi.create', compact('page_title', 'page_description'));
    }

    public function store(RegulasiRequest $request)
    {
        try {
            $input = $request->input();
            $input['profil_id'] = $this->profil->id;

            if ($request->hasFile('file_regulasi')) {
                $lampiran1 = $request->file('file_regulasi');
                $fileName1 = $lampiran1->getClientOriginalName();
                $path      = "storage/regulasi/";
                $request->file('file_regulasi')->move($path, $fileName1);

                $input['file_regulasi'] = $path . $fileName1;
                $input['mime_type'] = $lampiran1->getClientOriginalExtension();
            }

            Regulasi::create($input);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Regulasi gagal disimpan!!');
        }

        return redirect()->route('informasi.regulasi.index')->with('success', 'Regulasi berhasil disimpan!');
    }

    public function show(Regulasi $regulasi)
    {
        $page_title       = "Regulasi";
        $page_description = "Detail Regulasi : " . $page_title;

        return view('informasi.regulasi.show', compact('page_title', 'page_description', 'regulasi'));
    }

    public function edit(Regulasi $regulasi)
    {
        $page_title       = 'Regulasi';
        $page_description = 'Ubah Regulasi : ' . $regulasi->judul;

        return view('informasi.regulasi.edit', compact('page_title', 'page_description', 'regulasi'));
    }

    public function update(RegulasiRequest $request, Regulasi $regulasi)
    {
        try {
            $input = $request->input();
            $input['profil_id'] = $this->profil->id;

            if ($request->hasFile('file_regulasi')) {
                $lampiran1 = $request->file('file_regulasi');
                $fileName1 = $lampiran1->getClientOriginalName();
                $path      = "storage/regulasi/";
                $lampiran1->move($path, $fileName1);
                unlink(base_path('public/' . $regulasi->file_regulasi));

                $input['file_regulasi'] = $path . $fileName1;
                $input['mime_type'] = $lampiran1->getClientOriginalExtension();
            }
            $regulasi->update($input);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Regulasi gagal disimpan!!');
        }

        return redirect()->route('informasi.regulasi.show', $regulasi->id)->with('success', 'Regulasi berhasil disimpan!');
    }

    public function destroy(Regulasi $regulasi)
    {
        try {
            if ($regulasi->delete()) {
                unlink(base_path('public/' . $regulasi->file_regulasi));
            }
        } catch (\Exception $e) {
            return redirect()->route('informasi.regulasi.index')->with('error', 'Regulasi gagal dihapus!');
        }

        return redirect()->route('informasi.regulasi.index')->with('success', 'Regulasi sukses dihapus!');
    }

    public function download(Regulasi $regulasi)
    {
        try {
            return response()->download($regulasi->file_regulasi);
        } catch (\Exception $e) {
            return back()->with('error', 'Dokumen regulasi tidak ditemukan');
        }
    }
}
