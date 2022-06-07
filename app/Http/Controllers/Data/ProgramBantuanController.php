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
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\SinkronBantuan;
use App\Imports\SinkronPesertaBantuan;
use App\Models\DataDesa;
use App\Models\PesertaProgram;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProgramBantuanController extends Controller
{
    public function index()
    {
        $page_title       = 'Program Bantuan';
        $page_description = 'Daftar Program Bantuan';
        $list_desa        = DataDesa::all();

        return view('data.program_bantuan.index', compact('page_title', 'page_description', 'list_desa'));
    }

    public function getaProgramBantuan(Request $request)
    {
        return DataTables::of(Program::when(!empty($request->input('desa')), fn ($q) => $q->where('desa_id', $request->desa))->with('desa'))
            ->addColumn('aksi', function ($row) {
                $data['detail_url'] = route('data.program-bantuan.show', [$row->id, $row->desa_id]);

                return view('forms.aksi', $data);
            })
            ->addColumn('masa_berlaku', function ($row) {
                return $row->start_date . ' - ' . $row->end_date;
            })
            ->editColumn('sasaran', function ($row) {
                $sasaran = [1 => 'Penduduk/Perorangan', 2 => 'Keluarga-KK'];
                return $sasaran[$row->sasaran];
            })
            ->rawColumns(['aksi'])->make();
    }

    public function show($id, $desa_id)
    {
        $program          = Program::with('desa')->findOrFail($id);
        $page_title       = 'Detail Program';
        $page_description = 'Program Bantuan ' . $program->nama;
        $sasaran          = [1 => 'Penduduk/Perorangan', 2 => 'Keluarga-KK'];
        $peserta          = PesertaProgram::where('program_id', $id)->where('desa_id', $desa_id)->get();

        return view('data.program_bantuan.show', compact('page_title', 'page_description', 'program', 'sasaran', 'peserta'));
    }

    public function import()
    {
        $page_title       = 'Impor';
        $page_description = 'Impor Data Program Bantuan';

        return view('data.program_bantuan.import', compact('page_title', 'page_description'));
    }

    public function do_import(Request $request)
    {
        $this->validate($request, [
            'file' => 'file|mimes:zip|max:51200',
        ]);

        try {
            // Upload file zip temporary.
            $file = $request->file('file');
            $file->storeAs('temp', $name = $file->getClientOriginalName());

            // Temporary path file
            $path = storage_path("app/temp/{$name}");
            $extract = storage_path('app/public/bantuan/');

            // Ekstrak file
            $zip = new \ZipArchive();
            $zip->open($path);
            $zip->extractTo($extract);
            $zip->close();

            glob($extract.'*.csv');

            (new SinkronBantuan())
                ->queue($extract . Str::replaceLast('zip', 'csv', $name));
            (new SinkronPesertaBantuan())
                ->queue($extract . Str::replaceLast('zip', 'csv', 'peserta_'.$name));
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Import data gagal. '. $e->getMessage());
        }

        return redirect()->route('data.program-bantuan.index')->with('success', 'Import data sukses.');
    }
}
