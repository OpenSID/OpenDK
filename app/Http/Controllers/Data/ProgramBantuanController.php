<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\PesertaProgram;
use App\Models\Program;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use function back;
use function compact;
use function redirect;
use function request;
use function route;
use function view;

class ProgramBantuanController extends Controller
{
    public function index()
    {
        $page_title       = 'Program Bantuan';
        $page_description = 'Data Program Bantuan';
        return view('data.program_bantuan.index', compact('page_title', 'page_description'));
    }

    public function getaProgramBantuan()
    {
        return DataTables::of(Program::all())
            ->addColumn('action', function ($row) {
                $edit_url   = route('data.program-bantuan.edit', $row->id);
                $delete_url = route('data.program-bantuan.destroy', $row->id);
                $show_url   = route('data.program-bantuan.show', $row->id);

                $data['detail_url'] = $show_url;
                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->addColumn('masa_berlaku', function ($row) {
                return $row->start_date . ' - ' . $row->end_date;
            })
            ->editColumn('sasaran', function ($row) {
                $sasaran = [1 => 'Penduduk/Perorangan', 2 => 'Keluarga-KK'];
                return $sasaran[$row->sasaran];
            })
            ->rawColumns(['action'])->make();
    }

    public function create()
    {
        $page_title       = 'Tambah';
        $page_description = 'Tambah Program Bantuan Baru';

        return view('data.program_bantuan.create', compact('page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        try {
            request()->validate([
                'sasaran'    => 'required',
                'nama'       => 'required',
                'start_date' => 'required|date',
                'end_date'   => 'required|date',
            ]);

            Program::create($request->all());

            return redirect()->route('data.program-bantuan.index')->with('success', 'Data berhasil disimpan!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $program          = Program::find($id);
        $page_title       = 'Detail Program';
        $page_description = 'Program Bantuan ' . $program->nama;
        $sasaran          = [1 => 'Penduduk/Perorangan', 2 => 'Keluarga-KK'];
        $peserta          = PesertaProgram::where('program_id', $id)->get();

        return view('data.program_bantuan.show', compact('page_title', 'page_description', 'program', 'sasaran', 'peserta'));
    }

    public function update(Request $request, $id)
    {
        try {
            request()->validate([
                'sasaran'    => 'required',
                'nama'       => 'required',
                'start_date' => 'required|date',
                'end_date'   => 'required|date',
            ]);

            $program = Program::find($id);
            $program->fill($request->all());
            $program->update();

            return redirect()->route('data.program-bantuan.index')->with('success', 'Data berhasil disimpan!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $program          = Program::find($id);
        $page_title       = 'Edit Program';
        $page_description = 'Program Bantuan ' . $program->nama;
        $sasaran          = [1 => 'Penduduk/Perorangan', 2 => 'Keluarga-KK'];
        $peserta          = PesertaProgram::where('program_id', $id)->get();

        return view('data.program_bantuan.edit', compact('page_title', 'page_description', 'program', 'sasaran', 'peserta'));
    }

    public function destroy($id)
    {
        try {
            Program::find($id)->delete();
            PesertaProgram::where('program_id', $id)->delete();

            return redirect()->route('data.program-bantuan.index')->with('success', 'Data berhasil dihapus!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Data gagal dihapus!' . $e->getMessage());
        }
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
        try {
            request()->validate([
                'peserta'       => 'required',
                'tanggal_lahir' => 'date',
            ]);

            PesertaProgram::create($request->all());

            return redirect()->route('data.program-bantuan.show', $request->input('program_id'))->with('success', 'Data berhasil disimpan!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!' . $e->getMessage());
        }
    }
}
