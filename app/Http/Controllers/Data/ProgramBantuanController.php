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

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\PesertaProgram;
use App\Models\Program;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProgramBantuanController extends Controller
{
    public function index()
    {
        $page_title       = 'Program Bantuan';
        $page_description = 'Daftar Program Bantuan';

        return view('data.program_bantuan.index', compact('page_title', 'page_description'));
    }

    public function getaProgramBantuan()
    {
        return DataTables::of(Program::all())
            ->addColumn('aksi', function ($row) {
                $data['detail_url'] = route('data.program-bantuan.show', $row->id);
                $data['edit_url']   = route('data.program-bantuan.edit', $row->id);
                $data['delete_url'] = route('data.program-bantuan.destroy', $row->id);

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

    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Program Bantuan Baru';

        return view('data.program_bantuan.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        request()->validate([
            'sasaran'    => 'required',
            'nama'       => 'required',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        try {
            Program::create($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->route('data.program-bantuan.index')->with('success', 'Data berhasil disimpan!');
    }

    public function show($id)
    {
        $program          = Program::findOrFail($id);
        $page_title       = 'Detail Program';
        $page_description = 'Program Bantuan ' . $program->nama;
        $sasaran          = [1 => 'Penduduk/Perorangan', 2 => 'Keluarga-KK'];
        $peserta          = PesertaProgram::where('program_id', $id)->get();

        return view('data.program_bantuan.show', compact('page_title', 'page_description', 'program', 'sasaran', 'peserta'));
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'sasaran'    => 'required',
            'nama'       => 'required',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        try {
            $program = Program::findOrFail($id);
            $program->fill($request->all());
            $program->update();
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->route('data.program-bantuan.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $program          = Program::findOrFail($id);
        $page_title       = 'Edit Program';
        $page_description = 'Program Bantuan ' . $program->nama;
        $sasaran          = [1 => 'Penduduk/Perorangan', 2 => 'Keluarga-KK'];
        $peserta          = PesertaProgram::where('program_id', $id)->get();

        return view('data.program_bantuan.edit', compact('page_title', 'page_description', 'program', 'sasaran', 'peserta'));
    }

    public function destroy($id)
    {
        try {
            Program::findOrFail($id)->delete();
            PesertaProgram::where('program_id', $id)->delete();
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('data.program-bantuan.index')->with('success', 'Data berhasil dihapus!');
    }

    public function createPeserta($id)
    {
        $program          = Program::findOrFail($id);
        $page_title       = 'Tambah Peserta';
        $page_description = 'Program Bantuan ' . $program->nama;
        $sasaran          = [1 => 'Penduduk/Perorangan', 2 => 'Keluarga-KK'];

        return view('data.program_bantuan.add_peserta', compact('page_title', 'page_description', 'program', 'sasaran'));
    }

    public function add_peserta(Request $request)
    {
        request()->validate([
            'peserta'       => 'required',
            'tanggal_lahir' => 'date',
        ]);

        try {
            PesertaProgram::create($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->route('data.program-bantuan.show', $request->input('program_id'))->with('success', 'Data berhasil disimpan!');
    }
}
