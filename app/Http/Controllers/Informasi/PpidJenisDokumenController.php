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

use App\Enums\PpidStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\PpidJenisDokumenRequest;
use App\Models\PpidJenisDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PpidJenisDokumenController extends Controller
{
    public function index()
    {
        $page_title = 'Jenis Dokumen PPID';
        $page_description = 'Daftar Jenis Dokumen PPID';

        return view('ppid.jenis-dokumen.index', compact('page_title', 'page_description'));
    }

    public function getData(Request $request)
    {
        $query = PpidJenisDokumen::query();

        return DataTables::of($query)
            ->addColumn('aksi', function ($row) {
                $data['show_url'] = route('ppid.jenis-dokumen.show', $row->id);
                $data['edit_url'] = route('ppid.jenis-dokumen.edit', $row->id);
                $data['delete_url'] = route('ppid.jenis-dokumen.destroy', $row->id);
                $data['lock_url'] = route('ppid.jenis-dokumen.toggle-kunci', $row->id);
                $data['is_locked'] = $row->is_kunci;

                return view('forms.aksi', $data);
            })
            ->editColumn('status', function ($row) {
                $badgeClass = $row->status === PpidStatusEnum::Aktif ? 'label-success' : 'label-danger';
                $label = PpidStatusEnum::options()[$row->status] ?? $row->status;
                $lockIcon = $row->is_kunci ? ' <i class="fa fa-lock"></i>' : '';

                return "<span class=\"label {$badgeClass}\">{$label}{$lockIcon}</span>";
            })
            ->editColumn('urutan', function ($row) {
                return '<span class="drag-handle" data-id="'.$row->id.'"><i class="fa fa-bars"></i> '.$row->urutan.'</span>';
            })
            ->addColumn('jumlah_dokumen', function ($row) {
                return $row->dokumen()->count();
            })
            ->filterColumn('status', function ($query, $keyword) {
                if (strtolower($keyword) === 'aktif') {
                    $query->where('status', 'aktif');
                } elseif (strtolower($keyword) === 'tidak aktif') {
                    $query->where('status', 'tidak_aktif');
                }
            })
            ->rawColumns(['aksi', 'status', 'urutan'])
            ->make(true);
    }

    public function create()
    {
        $page_title = 'Jenis Dokumen PPID';
        $page_description = 'Tambah Jenis Dokumen PPID';
        $status_options = PpidStatusEnum::options();

        return view('ppid.jenis-dokumen.create', compact('page_title', 'page_description', 'status_options'));
    }

    public function store(PpidJenisDokumenRequest $request)
    {
        try {
            $input = $request->validated();

            // Generate slug from nama if not provided
            if (empty($input['slug'])) {
                $input['slug'] = Str::slug($input['nama']);
            }

            // Get the highest urutan and add 1
            $maxUrutan = PpidJenisDokumen::max('urutan') ?? 0;
            $input['urutan'] = $input['urutan'] ?? ($maxUrutan + 1);

            PpidJenisDokumen::create($input);

            return redirect()->route('ppid.jenis-dokumen.index')
                ->with('success', 'Jenis dokumen PPID berhasil ditambahkan!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Jenis dokumen PPID gagal ditambahkan!');
        }
    }

    public function show(PpidJenisDokumen $jenis_dokumen)
    {
        $page_title = 'Jenis Dokumen PPID';
        $page_description = 'Detail Jenis Dokumen PPID';

        return view('ppid.jenis-dokumen.show', compact('page_title', 'page_description', 'jenis_dokumen'));
    }

    public function edit(PpidJenisDokumen $jenis_dokumen)
    {
        $page_title = 'Jenis Dokumen PPID';
        $page_description = 'Ubah Jenis Dokumen PPID';
        $status_options = PpidStatusEnum::options();

        return view('ppid.jenis-dokumen.edit', compact('page_title', 'page_description', 'jenis_dokumen', 'status_options'));
    }

    public function update(PpidJenisDokumenRequest $request, PpidJenisDokumen $jenis_dokumen)
    {
        try {
            $input = $request->validated();

            // Prevent editing slug for locked records
            if ($jenis_dokumen->is_kunci) {
                unset($input['slug']);
            }

            $jenis_dokumen->update($input);

            return redirect()->route('ppid.jenis-dokumen.index')
                ->with('success', 'Jenis dokumen PPID berhasil diubah!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Jenis dokumen PPID gagal diubah!');
        }
    }

    public function destroy(PpidJenisDokumen $jenis_dokumen)
    {
        try {
            // Prevent deleting locked records
            if ($jenis_dokumen->is_kunci) {
                return redirect()->route('ppid.jenis-dokumen.index')
                    ->with('error', 'Jenis dokumen ini terkunci dan tidak dapat dihapus!');
            }

            $jenis_dokumen->delete();

            return redirect()->route('ppid.jenis-dokumen.index')
                ->with('success', 'Jenis dokumen PPID berhasil dihapus!');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('ppid.jenis-dokumen.index')
                ->with('error', 'Jenis dokumen PPID gagal dihapus!');
        }
    }

    public function toggleKunci(PpidJenisDokumen $jenis_dokumen)
    {
        try {
            $jenis_dokumen->update([
                'is_kunci' => ! $jenis_dokumen->is_kunci,
            ]);

            return redirect()->route('ppid.jenis-dokumen.index')
                ->with('success', 'Status kunci berhasil diubah!');
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Status kunci gagal diubah!');
        }
    }

    public function reorder(Request $request)
    {
        try {
            $orders = $request->input('order', []);

            foreach ($orders as $index => $id) {
                PpidJenisDokumen::where('id', $id)->update(['urutan' => $index + 1]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            report($e);

            return response()->json(['success' => false], 500);
        }
    }
}
