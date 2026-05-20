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

use App\Models\Artikel;
use App\Models\ArtikelKategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArtikelRequest;
use Illuminate\Support\Facades\Log;

class ArtikelController extends Controller
{
    use HandlesFileUpload;

    public function index(): View
    {
        $page_title = 'Artikel';
        $page_description = 'Daftar Artikel';

        $kategori = \App\Models\ArtikelKategori::get();

        return view('informasi.artikel.index', compact('page_title', 'page_description', 'kategori'));
    }

    public function getDataArtikel(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->ajax()) {
            $query = Artikel::with('kategori');

            if ($request->has('id_kategori') && $request->id_kategori != '') {
                $query->where('id_kategori', $request->id_kategori);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('aksi', function (Artikel $row) {
                    $data['show_web'] = route('berita.detail', $row->slug);

                    if (!auth()->guest()) {
                        $data['edit_url'] = auth()->user()->can('access.informasi.artikel.edit') ? route('informasi.artikel.edit', $row->id) : null;
                        $data['delete_url'] = auth()->user()->can('access.informasi.artikel.delete') ? route('informasi.artikel.destroy', $row->id) : null;
                    }

                    return view('forms.aksi', $data);
                })
                ->addColumn('kategori', function (Artikel $row): string {
                    return $row->kategori ? $row->kategori->nama_kategori : '-';
                })
                ->editColumn('status', function (Artikel $row): string {
                    if ($row->status == 0) {
                        return '<span class="label label-danger">Tidak Aktif</span>';
                    } else {
                        return '<span class="label label-success">Aktif</span>';
                    }
                })
                ->editColumn('tanggal_terbit', function (Artikel $row): string {
                    return $row->tanggal_terbit ? format_date($row->tanggal_terbit) : '-';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return response()->json(['error' => 'Method not allowed'], 405);
    }

    public function create(): View
    {
        $page_title = 'Artikel';
        $page_description = 'Tambah Artikel';

        $kategori = ArtikelKategori::where('status', 'Ya')->pluck('nama_kategori', 'id_kategori');

        return view('informasi.artikel.create', compact('page_title', 'page_description', 'kategori'));
    }

    public function store(ArtikelRequest $request): RedirectResponse
    {
        try {
            $input = $request->all();
            $input['tanggal_terbit'] = $request->tanggal_terbit;
            $this->handleFileUpload($request, $input, 'gambar', 'artikel', false);

            Artikel::create($input);
        } catch (\Exception $e) {
            Log::error('Artikel creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->withInput()->with('error', 'Simpan artikel gagal!');
        }

        return redirect()->route('informasi.artikel.index')->with('success', 'Artikel berhasil disimpan!');
    }

    public function edit(Artikel $artikel): View
    {
        $page_title = 'Artikel';
        $page_description = 'Ubah Artikel';

        $kategori = ArtikelKategori::where('status', 'Ya')->pluck('nama_kategori', 'id_kategori');

        return view('informasi.artikel.edit', compact('artikel', 'page_title', 'page_description', 'kategori'));
    }

    public function update(ArtikelRequest $request, Artikel $artikel): RedirectResponse
    {
        try {
            $input = $request->all();
            $input['tanggal_terbit'] = $request->tanggal_terbit;
            $this->handleFileUpload($request, $input, 'gambar', 'artikel', false);

            $artikel->update($input);
        } catch (\Exception $e) {
            Log::error('Artikel update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'artikel_id' => $artikel->id,
            ]);

            return back()->withInput()->with('error', 'Artikel gagal dihapus!');
        }

        return redirect()->route('informasi.artikel.index')->with('success', 'Artikel berhasil diubah!');
    }

    public function destroy(Artikel $artikel): RedirectResponse
    {
        try {
            $artikel->delete();
        } catch (\Exception $e) {
            Log::error('Artikel deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'artikel_id' => $artikel->id,
            ]);

            return redirect()->route('informasi.artikel.index')->with('error', 'Artikel gagal dihapus!');
        }

        return redirect()->route('informasi.artikel.index')->with('success', 'Artikel sukses dihapus!');
    }
}
