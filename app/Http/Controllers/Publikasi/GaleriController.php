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
use App\Http\Requests\GaleriRequest;
use App\Models\Album;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class GaleriController extends Controller
{

    public function index(Album $album)
    {
        $page_title = 'Album';
        $page_description = 'Daftar Album';

        Session::put('album_id', $album->id);

        return view('publikasi.galeri.index', compact('page_title', 'page_description', 'album'));
    }

    public function getDataGaleri(Request $request, Album $album)
    {
        if ($request->ajax()) {
            return DataTables::of(Galeri::where('album_id', $album->id))
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    // $data['show_web'] = route('berita.detail', $row->slug);

                    if (! auth()->guest()) {
                        $data['edit_url'] = route('publikasi.galeri.edit', $row->id);
                        $data['delete_url'] = route('publikasi.galeri.destroy', $row->id);
                        if ($row->status == 1) {
                            $data['unlock_url'] = route('publikasi.galeri.status', $row->id);
                        } else {
                            $data['lock_url'] = route('publikasi.galeri.status', $row->id);
                        }
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
        $page_title = 'Galeri';
        $page_description = 'Tambah Galeri';

        return view('publikasi.galeri.create', compact('page_title', 'page_description'));
    }

    public function store(GaleriRequest $request)
    {
        try {
            $input = $request->all();

            $imageNames = [];
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    $path = Storage::putFile('public/publikasi/galeri', $file);

                    $imageNames[] = $path;
                }
                $input['gambar'] = $imageNames;
            }

            $input['album_id'] = Session::get('album_id');

            Galeri::create($input);

            return redirect()->route('publikasi.galeri.index', Session::get('album_id'))->with('success', 'Album berhasil disimpan!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Simpan album gagal!');
        }
    }

    public function status(Galeri $galeri)
    {

        try {
            $galeri->update([
                'status' => $galeri->status == 1 ? 0 : 1
            ]);

            flash()->success(trans('general.active-success'));

            return redirect()->route('publikasi.galeri.index', Session::get('album_id'));
        } catch (\Exception $e) {
            report($e);
            flash()->success(trans('general.active-error'));

            return redirect()->route('publikasi.galeri.index', Session::get('album_id'));
        }
    }

    public function edit(Galeri $galeri)
    {
        $page_title = 'Galeri';
        $page_description = 'Ubah Galeri';

        return view('publikasi.galeri.edit', compact('galeri', 'page_title', 'page_description'));
    }

    public function update(GaleriRequest $request, Galeri $galeri)
    {
        try {
            $input = $request->all();

            $imageNames = [];
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    $path = Storage::putFile('public/publikasi/galeri', $file);

                    $imageNames[] = $path;
                }
                $input['gambar'] = $imageNames;
            }

            $input['link'] = $input['jenis'] == 'file' ?  null : $input['link'];

            $galeri->update($input);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Galeri gagal dihapus!');
        }

        return redirect()->route('publikasi.galeri.index', Session::get('album_id'))->with('success', 'Galeri berhasil diubah!');
    }

    public function destroy(Galeri $galeri)
    {
        try {
            $galeri->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('publikasi.galeri.index', Session::get('album_id'))->with('error', 'Galeri gagal dihapus!');
        }

        return redirect()->route('publikasi.galeri.index', Session::get('album_id'))->with('success', 'Galeri sukses dihapus!');
    }
}
