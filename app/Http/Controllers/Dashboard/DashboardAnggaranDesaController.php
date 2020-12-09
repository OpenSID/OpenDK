<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\CoaType;
use App\Models\Profil;
use Illuminate\Support\Facades\DB;

use function compact;
use function config;
use function request;
use function view;
use function years_list;

class DashboardAnggaranDesaController extends Controller
{
  /**
   * Menampilkan Data Anggaran Dan realisasi Kecamatan
   **/
    public function showAnggaranDesa()
    {
        Counter::count('statistik.anggaran-desa');

        $data['page_title']       = 'Anggaran Desa (APBDes)';
        $data['page_description'] = 'Data Anggaran Desa (APBDes)';
        $defaultProfil            = config('app.default_profile');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        $data['list_desa']        = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();

        return view('pages.anggaran_desa.show_anggaran_desa')->with($data);
    }

    public function getChartAnggaranDesa()
    {
        $mid  = request('mid');
        $did  = request('did');
        $year = request('y');

        // Grafik Data Pendidikan
        $data_anggaran = [];
        $type          = CoaType::all();

        if ($mid == 'ALL' && $year == 'ALL') {
            $tmp = [];
            foreach ($type as $val) {
                $query_anggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id . '%');
                if ($did != 'ALL') {
                    $query_anggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id . ' - ' . $val->type_name,
                    'jumlah'   => $query_anggaran->sum('jumlah'),
                ];
            }

            $data_anggaran['grafik'] = $tmp;
        } elseif ($mid != 'ALL' && $year == 'ALL') {
            $tmp = [];
            foreach ($type as $val) {
                $query_anggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id . '%')
                    ->where('bulan', $mid);
                if ($did != 'ALL') {
                    $query_anggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id . ' - ' . $val->type_name,
                    'jumlah'   => $query_anggaran->sum('jumlah'),
                ];
            }

            $data_anggaran['grafik'] = $tmp;
        } elseif ($mid == 'ALL' && $year != 'ALL') {
            $tmp = [];
            foreach ($type as $val) {
                $query_anggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id . '%')
                    ->where('tahun', $year);
                if ($did != 'ALL') {
                    $query_anggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id . ' - ' . $val->type_name,
                    'jumlah'   => $query_anggaran->sum('jumlah'),
                ];
            }
            $data_anggaran['grafik'] = $tmp;
        } elseif ($mid != 'ALL' && $year != 'ALL') {
            $tmp = [];
            foreach ($type as $val) {
                $query_anggaran = DB::table('das_anggaran_desa')->select('*')
                    ->where('no_akun', 'LIKE', $val->id . '%')
                    ->where('bulan', $mid)
                    ->where('tahun', $year);
                if ($did != 'ALL') {
                    $query_anggaran->where('desa_id', $did);
                }
                $tmp[] = [
                    'anggaran' => $val->id . ' - ' . $val->type_name,
                    'jumlah'   => $query_anggaran->sum('jumlah'),
                ];
            }
            $data_anggaran['grafik'] = $tmp;
        }
        $data_anggaran['detail'] = view('dashboard.anggaran_desa.detail_anggaran', compact('did', 'mid', 'year'))->render();
        return $data_anggaran;
    }
}
