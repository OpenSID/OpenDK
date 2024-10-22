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

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KomentarArtikelController extends Controller
{
    public function index()
    {
        $page_title = 'Komentar Artikel';
        $page_description = 'Daftar Komentar Artikel';

        return view('informasi.komentar_artikel.index', compact('page_title', 'page_description'));
    }

    public function getDataKomentar(Request $request)
    {
        if ($request->ajax()) {
            $comments = Comment::with(['artikel'])->get();

            return DataTables::of($comments)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {

                    if (!auth()->guest()) {
                        $data['delete_url'] = route('informasi.komentar-artikel.destroy', $row->id);
                    }

                    return view('forms.aksi', $data);
                })
                ->editColumn('user', function ($row) {
                    $nama = $row->nama ? '<strong>' . $row->nama . '</strong></br>' : 'User tidak ditemukan';
                    $email = $row->email ? '<a href="mailto:' . $row->email . '">' . $row->email . '</a>' : '';
                    return $nama . $email;
                })
                ->editColumn('komentar', function ($row) {
                    return \Illuminate\Support\Str::limit($row->body, 100, '...');
                })
                ->editColumn('artikel', function ($row) {
                    $artikel = \Illuminate\Support\Str::limit($row->artikel->judul, 30, '...');
                    $tampil = $artikel . '<br><a target="_blank" href="' . route('berita.detail', $row->artikel->slug) . '">Lihat Artikel</a> ';
                    return $tampil;
                })
                ->editColumn('status', function ($row) {
                    $checked = $row->status == 'enable' ? 'checked' : '';
                    return '<label class="switch">
                            <input type="checkbox" class="toggle-status" data-id="' . $row->id . '" ' . $checked . '>
                            <span class="slider"></span>
                        </label>';
                })
                ->editColumn('created_at', function ($row) {
                    return format_datetime($row->created_at);
                })
                ->rawColumns(['aksi', 'user', 'artikel', 'status'])
                ->make(true);
        }
    }

    public function updateStatus(Request $request)
    {
        $comment = Comment::find($request->id);
        $comment->status = $request->status;
        $comment->save();

        return response()->json(['success' => 'Status updated successfully.']);
    }


    public function destroy($id)
    {
        try {
            // Temukan komentar berdasarkan ID
            $comment = Comment::find($id);

            // Jika komentar tidak ditemukan, kembalikan respon error
            if (!$comment) {
                return redirect()->route('informasi.komentar-artikel.index')
                    ->with('error', 'Komentar tidak ditemukan!');
            }

            // Hapus komentar
            $comment->delete();

            // Kembalikan respon sukses
            return redirect()->route('informasi.komentar-artikel.index')
                ->with('success', 'Komentar berhasil dihapus!');
        } catch (\Exception $e) {
            // Tangani pengecualian
            report($e);

            // Kembalikan respon error
            return redirect()->route('informasi.komentar-artikel.index')
                ->with('error', 'Komentar gagal dihapus!');
        }
    }
}
