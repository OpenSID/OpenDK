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

namespace App\Http\Controllers\Ppid;

use App\Http\Controllers\Controller;
use App\Models\PpidJenisDokumen;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class JenisDokumenPpidController extends Controller
{
    protected $page_title = 'PPID';
    protected $icons = [
        'fa fa-file',
        'fa fa-file-text',
        'fa fa-book',
        'fa fa-folder',
        'fa fa-folder-open',
        'fa fa-archive',
        'fa fa-info-circle',
        'fa fa-balance-scale',
        'fa fa-legal',
        'fa fa-gavel',
        'fa fa-university',
        'fa fa-globe',
        'fa fa-users',
        'fa fa-camera',
        'fa fa-download'
    ];

    public function __construct()
    {
        view()->share('page_title', $this->page_title);
        view()->share('icons', $this->icons);
    }

    public function index()
    {
        $page_description = 'Daftar Jenis Dokumen';

        return view('ppid.jenis_dokumen.index', compact('page_description'));
    }

    public function getData(Request $request)
    {
        $query = PpidJenisDokumen::query();

        if ($request->filled('jenis_dokumen_id')) {
            $query->where('id', $request->jenis_dokumen_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->order(function ($query) use ($request) {
                // Default sorting
                if ($request->has('order')) {
                    $columns = $request->get('columns');
                    $order = $request->get('order')[0];
                    $columnName = $columns[$order['column']]['data'];
                    $dir = $order['dir'];

                    $query->orderBy($columnName, $dir);
                } else {
                    $query->orderBy('urut', 'asc');
                }
            })
            ->addColumn('checkbox', function ($row) {
                $protectedSlugs = ['secara-berkala', 'serta-merta', 'tersedia-setiap-saat'];
                $disabled = in_array($row->slug, $protectedSlugs) ? 'disabled' : '';
                return '<input type="checkbox" class="checkbox-item" value="' . $row->id . '" ' . $disabled . '>';
            })
            ->addColumn('drag_handle', function ($row) {
                return '<i class="fa fa-bars handle" style="cursor:move; color: #999;"></i>';
            })
            ->addColumn('aksi', function ($row) {
                $data = [
                    'id' => $row->id,
                    'drag' => true,
                ];

                if (!auth()->guest()) {
                    $data['edit_url'] = route('ppid.jenis-dokumen.edit', $row->id);

                    // Logic Protected Slugs
                    $protectedSlugs = ['secara-berkala', 'serta-merta', 'tersedia-setiap-saat'];
                    $data['delete_url'] = !in_array($row->slug, $protectedSlugs)
                        ? route('ppid.jenis-dokumen.destroy', $row->id)
                        : null;

                    // Status URL Logic
                    $statusUrl = route('ppid.jenis-dokumen.status', $row->id);
                    if ($row->status == 0) {
                        $data['lock_url'] = $statusUrl;
                        $data['unlock_url'] = null;
                    } else {
                        $data['lock_url'] = null;
                        $data['unlock_url'] = $statusUrl;
                    }
                }

                return view('forms.aksi', $data);
            })
            ->editColumn('icon', function ($row) {
                $bgColor = !empty($row->kode) ? $row->kode : '#ffffff';
                $iconColor = 'white';

                return '<div class="text-center" style="
                width: 35px; 
                height: 35px; 
                margin: auto; 
                border: 1px solid #e1e1e1; 
                border-radius: 10px; 
                background-color: ' . $bgColor . '; 
                display: flex; 
                align-items: center; 
                justify-content: center;">
                <i class="' . ($row->icon ?? 'fa fa-file') . '" aria-hidden="true" title="' . ($row->icon ?? 'fa fa-file') . '" style="color: ' . $iconColor . '; font-size: 1.2rem;"></i>
            </div>';
            })
            ->editColumn('status', function ($row) {
                return $row->getStatusLabelAttribute();
            })
            ->rawColumns(['checkbox', 'drag_handle', 'aksi', 'icon', 'status'])
            ->make(true);
    }

    public function create()
    {
        $page_description = 'Tambah Jenis Dokumen';

        return view('ppid.jenis_dokumen.create', compact('page_description'));
    }

    public function store(Request $request)
    {
        $request->merge(['slug' => \Str::slug($request->nama)]);
        $request->validate([
            'nama' => 'required|string|max:150',
            'slug' => 'required|unique:ppid_jenis_dokumen,slug',
        ], [
            'slug.unique' => 'Nama Jenis Dokumen sudah ada.'
        ]);
        try {
            PpidJenisDokumen::create([
                'nama'      => $request->nama,
                'slug'      => Str::slug($request->nama),
                'deskripsi' => $request->deskripsi,
                'kode'      => $request->kode ?? '#ffffff',
                'icon'      => $request->icon ?? 'fa fa-file',
                'status'    => '1',
                'urut'      => (PpidJenisDokumen::max('urut') ?? 0) + 1,
            ]);

            return redirect()->route('ppid.jenis-dokumen.index')->with('success', 'Jenis Dokumen berhasil ditambahkan');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Jenis Dokumen gagal disimpan!');
        }
    }

    public function edit($id)
    {
        $jenis = PpidJenisDokumen::findOrFail($id);
        $page_description = 'Edit Jenis Dokumen';

        return view('ppid.jenis_dokumen.edit', compact('jenis', 'page_description'));
    }

    public function update(Request $request, $id)
    {
        $slug = Str::slug($request->nama);
        $request->merge(['slug' => $slug]);
        $request->validate([
            'nama' => 'required|string|max:150',
            'slug' => 'required|unique:ppid_jenis_dokumen,slug,' . $id,
        ], [
            'nama.required' => 'Nama Jenis Dokumen wajib diisi.',
            'slug.unique'   => 'Nama ini sudah digunakan oleh dokumen lain.'
        ]);

        try {
            $jenis = PpidJenisDokumen::findOrFail($id);
            $dataUpdate = $request->only(['nama', 'deskripsi', 'kode', 'icon', 'status']);

            $jenis->update($dataUpdate);
            return redirect()->route('ppid.jenis-dokumen.index')->with('success', 'Jenis Dokumen berhasil diubah');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Jenis Dokumen gagal diubah!');
        }
    }

    public function destroy($id)
    {
        try {
            $jenis = PpidJenisDokumen::findOrFail($id);
            $protectedSlugs = ['secara-berkala', 'serta-merta', 'tersedia-setiap-saat'];

            if (in_array($jenis->slug, $protectedSlugs)) {
                return back()->with('error', 'Jenis Dokumen tidak boleh dihapus!');
            }

            $jenis->delete();
            return back()->with('success', 'Jenis Dokumen berhasil dihapus');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Jenis Dokumen gagal dihapus!');
        }
    }

    /**
     * Bulk Delete - Hapus Multiple Records
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:ppid_jenis_dokumen,id'
        ], [
            'ids.required' => 'Tidak ada data yang dipilih',
            'ids.array' => 'Format data tidak valid',
            'ids.min' => 'Pilih minimal 1 data untuk dihapus',
            'ids.*.exists' => 'Data tidak ditemukan'
        ]);

        try {
            $ids = $request->ids;
            $protectedSlugs = ['secara-berkala', 'serta-merta', 'tersedia-setiap-saat'];

            $protectedItems = PpidJenisDokumen::whereIn('id', $ids)
                ->whereIn('slug', $protectedSlugs)
                ->count();

            if ($protectedItems > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa data tidak dapat dihapus karena dilindungi sistem!'
                ], 422);
            }

            DB::beginTransaction();

            $deletedCount = PpidJenisDokumen::whereIn('id', $ids)
                ->whereNotIn('slug', $protectedSlugs)
                ->delete();

            DB::commit();

            if ($deletedCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil menghapus {$deletedCount} data"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dapat dihapus'
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fitur Drag & Drop untuk menyimpan urutan baru
     */
    public function updateOrder(Request $request)
    {
        // Validasi request
        $request->validate([
            'order' => 'required|array|min:1',
            'order.*.id' => 'required|integer|exists:ppid_jenis_dokumen,id',
            'order.*.position' => 'required|integer|min:1'
        ], [
            'order.required' => 'Data urutan tidak ditemukan',
            'order.array' => 'Format data tidak valid',
            'order.*.id.exists' => 'Data tidak ditemukan'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->order as $item) {
                PpidJenisDokumen::where('id', $item['id'])
                    ->update(['urut' => $item['position']]);
            }

            DB::commit();

            if ($request->ajax()) {

                return response()->json([
                    'success' => true,
                    'message' => 'Urutan Jenis Dokumen berhasil diperbarui'
                ]);
            }

            return redirect()->route('ppid.jenis-dokumen.index')
                ->with('success', 'Urutan Jenis Dokumen berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            if ($request->ajax()) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui urutan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal memperbarui urutan!');
        }
    }

    /**
     * Update status
     */
    public function status($id)
    {
        try {
            $jenis = PpidJenisDokumen::findOrFail($id);
            $jenis->status = ($jenis->status == '1') ? '0' : '1';
            $jenis->save();

            return redirect()->route('ppid.jenis-dokumen.index')
                ->with('success', 'Status Jenis Dokumen berhasil diubah!');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Status Jenis Dokumen gagal diubah!');
        }
    }
}
