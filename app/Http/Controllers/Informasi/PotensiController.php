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

use App\Models\Potensi;
use App\Models\TipePotensi;
use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\PotensiRequest;
use Yajra\DataTables\Facades\DataTables;

class PotensiController extends Controller
{
    use HandlesFileUpload;

    public function index()
    {
        $page_title = 'Potensi';
        $page_description = 'Daftar Potensi';
        $kategoriPotensi  = TipePotensi::all();

        return view('informasi.potensi.index', compact('page_title', 'page_description', 'kategoriPotensi'));
    }

    public function getDataPotensi()
    {
        return DataTables::of(Potensi::query())
            ->addColumn('aksi', function ($row) {
                $data['show_url'] = route('informasi.potensi.show', $row->id);

                if (! auth()->guest()) {
                    $data['edit_url'] = route('informasi.potensi.edit', $row->id);
                    $data['delete_url'] = route('informasi.potensi.destroy', $row->id);
                }

                return view('forms.aksi', $data);
            })
            ->make();
    }

    public function kategori()
    {
        $page_title = 'Potensi';

        if ($_GET['id'] != null) {
            $potensis = Potensi::where('kategori_id', $_GET['id'])->latest()->paginate(10);
            $kategori = TipePotensi::findOrFail($_GET['id'])->nama_kategori;
        } else {
            $potensis = Potensi::latest()->paginate(10);
            $kategori = 'Semua';
        }

        $page_description = 'Kategori Potensi : '.$kategori;

        return view('informasi.potensi.index', compact('page_title', 'page_description', 'potensis'));
    }

    public function create()
    {
        $page_title = 'Potensi';
        $page_description = 'Tambah Potensi';

        return view('informasi.potensi.create', compact('page_title', 'page_description'));
    }

    public function store(PotensiRequest $request)
    {
        try {
            $input = $request->input();

            $this->handleFileUpload($request, $input, 'file_gambar', 'potensi_kecamatan/');

            Potensi::create($input);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Simpan Event gagal!');
        }

        return redirect()->route('informasi.potensi.index')->with('success', 'Potensi berhasil disimpan!');
    }

    public function show(Potensi $potensi)
    {
        $page_title = 'Potensi';
        $page_description = 'Detail Potensi';

        return view('informasi.potensi.show', compact('page_title', 'page_description', 'potensi'));
    }

    public function edit(Potensi $potensi)
    {
        $page_title = 'Potensi';
        $page_description = 'Ubah Potensi';

        return view('informasi.potensi.edit', compact('page_title', 'page_description', 'potensi'));
    }

    public function update(PotensiRequest $request, Potensi $potensi)
    {
        try {
            $input = $request->all();

            $this->handleFileUpload($request, $input, 'file_gambar', 'potensi_kecamatan/');

            $potensi->update($input);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Data Potensi gagal disimpan!');
        }

        return redirect()->route('informasi.potensi.index')->with('success', 'Data Potensi berhasil disimpan!');
    }

    public function destroy(Potensi $potensi)
    {
        try {
            if ($potensi->delete()) {
                unlink(base_path('public/'.$potensi->file_gambar));
            }
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('informasi.form-dokumen.index')->with('error', 'Potensi gagal dihapus!');
        }

        return redirect()->route('informasi.potensi.index')->with('success', 'Potensi Berhasil dihapus!');
    }
}
