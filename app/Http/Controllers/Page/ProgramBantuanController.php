<?php

namespace App\Http\Controllers\Page;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

use function compact;
use function config;
use function request;
use function rtrim;
use function view;
use function years_list;

class ProgramBantuanController extends Controller
{
    /**
     * Menampilkan Data Program Bantuan
     **/
    public function showProgramBantuan()
    {
        Counter::count('statistik.program-bantuan');

        $page_title       = 'Program Bantuan';
        $page_description = 'Data Program Bantuan';
        $defaultProfil    = config('app.default_profile');
        $year_list        = years_list();
        $list_desa        = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();
        return view('pages.program_bantuan.show_program_bantuan', compact('page_title', 'page_description', 'defaultProfil', 'year_list', 'list_desa'));
    }

    public function getChartBantuanPenduduk()
    {
        $kid  = config('app.default_profile');
        $did  = request('did');
        $year = request('y');

        // Data Grafik Bantuan Penduduk/Perorangan
        $data    = [];
        $program = Program::where('sasaran', 1)->get();

        foreach ($program as $prog) {
            $query_result = DB::table('das_peserta_program')
                ->join('das_penduduk', 'das_peserta_program.peserta', '=', 'das_penduduk.nik')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                ->where('das_peserta_program.sasaran', '=', 1)
                ->where('das_peserta_program.program_id', '=', $prog->id);
            if ($year == 'ALL') {
                $query_result->whereRaw('YEAR(das_peserta_program.created_at) in (?)', $this->where_year_helper());
            } else {
                $query_result->where('das_penduduk.tahun', $year);
            }

            if ($did != 'ALL') {
                $query_result->where('das_penduduk.desa_id', '=', $did);
            }

            $data[] = ['program' => $prog->nama, 'value' => $query_result->count()];
        }
        return $data;
    }

    public function getChartBantuanKeluarga()
    {
        $kid  = config('app.default_profile');
        $did  = request('did');
        $year = request('y');

        // Data Grafik Bantuan Penduduk/Perorangan
        $data    = [];
        $program = Program::where('sasaran', 2)->get();

        foreach ($program as $prog) {
            $query_result = DB::table('das_peserta_program')
                ->join('das_penduduk', 'das_peserta_program.peserta', '=', 'das_penduduk.no_kk')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                ->where('das_peserta_program.sasaran', '=', 2)
                ->where('das_peserta_program.program_id', '=', $prog->id);
            if ($year == 'ALL') {
                $query_result->whereRaw('YEAR(das_peserta_program.created_at) in (?)', $this->where_year_helper());
            } else {
                $query_result->where('das_penduduk.tahun', $year);
            }

            if ($did != 'ALL') {
                $query_result->where('das_penduduk.desa_id', '=', $did);
            }
            $query_result->groupBy('das_penduduk.no_kk');

            $data[] = ['program' => $prog->nama, 'value' => $query_result->count()];
        }
        return $data;
    }

    protected function where_year_helper()
    {
        $str = '';
        foreach (years_list() as $year) {
            $str .= $year . ',';
        }
        return rtrim($str, ',');
    }
}
