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

use App\Models\Regulasi;
use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegulasiRequest;
use App\Http\Requests\RegulasiUpdateRequest;
use App\Models\TipeRegulasi;
use Yajra\DataTables\Facades\DataTables;

class RegulasiController extends Controller
{
    use HandlesFileUpload;

    public function index()
    {
        $page_title = 'Regulasi';
        $page_description = 'Daftar Regulasi';
        $adaTipeRegulasi = TipeRegulasi::count() ? true : false;

        return view('informasi.regulasi.index', compact('page_title', 'page_description', 'adaTipeRegulasi'));
    }

    public function getDataRegulasi()
    {
        return DataTables::of(Regulasi::query())
            ->addColumn('aksi', function ($row) {
                $data['show_url'] = route('informasi.regulasi.show', $row->id);

                if (! auth()->guest()) {
                    $data['edit_url'] = route('informasi.regulasi.edit', $row->id);
                    $data['delete_url'] = route('informasi.regulasi.destroy', $row->id);
                }

                $data['download_url'] = route('informasi.regulasi.download', $row->id);

                return view('forms.aksi', $data);
            })->make();
    }

    public function create()
    {
        $page_title = 'Regulasi';
        $page_description = 'Tambah Regulasi';        

        return view('informasi.regulasi.create', compact('page_title', 'page_description'));
    }

    public function store(RegulasiRequest $request)
    {
        try {
            $input = $request->input();
            $input['profil_id'] = $this->profil->id;
            $this->handleFileUpload($request, $input, 'file_regulasi', 'regulasi');

            $input['mime_type'] = $request->file('file_regulasi')->getMimeType();
            Regulasi::create($input);

            return redirect()->route('informasi.regulasi.index')->with('success', 'Regulasi berhasil disimpan!');

        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Regulasi gagal disimpan!!');
        }

    }

    public function show(Regulasi $regulasi)
    {
        $page_title = 'Regulasi';
        $page_description = 'Detail Regulasi';

        return view('informasi.regulasi.show', compact('page_title', 'page_description', 'regulasi'));
    }

    public function edit(Regulasi $regulasi)
    {
        $page_title = 'Regulasi';
        $page_description = 'Ubah Regulasi';

        return view('informasi.regulasi.edit', compact('page_title', 'page_description', 'regulasi'));
    }

    public function update(RegulasiUpdateRequest $request, Regulasi $regulasi)
    {
        try {
            $input = $request->input();
            $input['profil_id'] = $this->profil->id;
            $this->handleFileUpload($request, $input, 'file_regulasi', 'regulasi');

            
            if ($request->hasFile('file_regulasi')) {
                $input['mime_type'] = $request->file('file_regulasi')->getMimeType();
            }

            $regulasi->update($input);

            return redirect()->route('informasi.regulasi.show', $regulasi->id)->with('success', 'Regulasi berhasil disimpan!');

        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Regulasi gagal disimpan!!');
        }

    }

    public function destroy(Regulasi $regulasi)
    {
        try {
            $regulasi->delete();

            return redirect()->route('informasi.regulasi.index')->with('success', 'Regulasi sukses dihapus!');

        } catch (\Exception $e) {
            report($e);

            return redirect()->route('informasi.regulasi.index')->with('error', 'Regulasi gagal dihapus!');
        }

    }

    public function download(Regulasi $regulasi)
    {
        try {
            return response()->download($regulasi->file_regulasi);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Dokumen regulasi tidak ditemukan');
        }
    }
}
