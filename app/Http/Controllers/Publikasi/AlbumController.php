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

namespace App\Http\Controllers\Publikasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AlbumController extends Controller
{
    public function index()
    {
        $page_title = 'Album';
        $page_description = 'Daftar Album';

        return view('publikasi.album.index', compact('page_title', 'page_description'));
    }

    public function getDataAlbum(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Album::all())
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    // $data['show_web'] = route('berita.detail', $row->slug);

                    if (! auth()->guest()) {
                        $data['edit_url'] = route('publikasi.album.edit', $row->id);
                        $data['delete_url'] = route('publikasi.album.destroy', $row->id);
                        if ($row->status == 1) {
                            $data['unlock_url'] = route('publikasi.album.status', $row->id);
                        } else {
                            $data['lock_url'] = route('publikasi.album.status', $row->id);
                        }
                        $data['detail_url'] = route('publikasi.galeri.index', $row->id);
                    }

                    return view('forms.aksi', $data);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 0) {
                        return '<span class="label label-danger">Tidak Aktif</span>';
                    } else {
                        return '<span class="label label-success">Aktif</span>';
                    }
                })
                ->editColumn('dibuat', function ($row) {
                    return format_datetime($row->created_at);
                })
                ->rawColumns(['aksi', 'status', 'dibuat'])
                ->make(true);
        }
    }

    public function create()
    {
        $page_title = 'Album';
        $page_description = 'Tambah Album';

        return view('publikasi.album.create', compact('page_title', 'page_description'));
    }

    public function show(Album $album)
    {
        $page_title = 'Album';
        $page_description = 'List Album';

        return view('publikasi.album.detail', compact('album', 'page_title', 'page_description'));
    }

    public function store(AlbumRequest $request)
    {
        try {
            $input = $request->all();
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar')->store('public/publikasi/album');
                $input['gambar'] = basename($file);
            }

            Album::create($input);

            return redirect()->route('publikasi.album.index')->with('success', 'Album berhasil disimpan!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Simpan album gagal!');
        }
    }

    public function status(Album $album)
    {
        try {
            $album->update([
                'status' => $album->status == 1 ? 0 : 1,
            ]);

            flash()->success(trans('general.active-success'));

            return redirect()->route('publikasi.album.index');
        } catch (\Exception $e) {
            report($e);
            flash()->success(trans('general.active-error'));

            return redirect()->route('publikasi.album.index');
        }
    }

    public function edit(Album $album)
    {
        $page_title = 'Album';
        $page_description = 'Ubah Album';

        return view('publikasi.album.edit', compact('album', 'page_title', 'page_description'));
    }

    public function update(AlbumRequest $request, Album $album)
    {
        try {
            $input = $request->all();

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar')->store('public/publikasi/album');
                $input['gambar'] = basename($file);
            }

            $album->update($input);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Album gagal dihapus!');
        }

        return redirect()->route('publikasi.album.index')->with('success', 'Album berhasil diubah!');
    }

    public function destroy(Album $album)
    {
        try {
            $album->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('publikasi.album.index')->with('error', 'Album gagal dihapus!');
        }

        return redirect()->route('publikasi.album.index')->with('success', 'Album sukses dihapus!');
    }
}
