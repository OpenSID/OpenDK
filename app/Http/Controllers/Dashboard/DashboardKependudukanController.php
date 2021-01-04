<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\Profil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use function array_merge;
use function array_sort;
use function config;
use function convert_born_date_to_age;
use function date;
use function env;
use function number_format;
use function random_color;
use function request;
use function strtolower;
use function ucfirst;
use function view;

class DashboardKependudukanController extends Controller
{
    /**
     * Menampilkan Data Kependudukan
     **/
    public function showKependudukan()
    {
        Counter::count('dashboard.kependudukan');

        $data['page_title']       = 'Kependudukan';
        $data['page_description'] = 'Statistik Kependudukan';
        $defaultProfil            = config('app.default_profile');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = $this->years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        $data['list_desa']        = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();

        $data = array_merge($data, $this->createDashboardKependudukan($defaultProfil, 'ALL', date('Y')));

        return view('dashboard.kependudukan.show_kependudukan')->with($data);
    }

    /* Menghasilkan array berisi semua tahun di mana penduduk tercatat sampai tahun sekarang */
    protected function years_list()
    {
        if (DB::table('das_penduduk')->first() == null) {
            return [];
        }
        $tahun_tertua = DB::table('das_penduduk')
            ->select(DB::raw('YEAR(created_at) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->limit(1)
            ->get()->first()->tahun;
        $tahun_tertua = $tahun_tertua ?: date("Y");
        $years = [];
        for ($y = $tahun_tertua; $y <= date("Y"); $y++) {
            $years[] = $y;
        }
        return array_reverse($years);
    }

    public function showKependudukanPartial()
    {
        $data['page_title']       = 'Kependudukan';
        $data['page_description'] = 'Statistik Kependudukan';
        $defaultProfil            = env('DAS_DEFAULT_PROFIL', '1');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = $this->years_list();

        if (! empty(request('kid')) && ! empty(request('did')) && request('y')) {
            $data = array_merge($data, $this->createDashboardKependudukan(request('kid'), request('did'), request('y')));
        }

        return $data;
    }

    protected function createDashboardKependudukan($kid, $did, $year)
    {
        $data = [];

        // Get Total Penduduk
        $query_total_penduduk = DB::table('das_penduduk')
           // ->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
            ->where('das_penduduk.kecamatan_id', '=', $kid)
            //->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
            ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
        if ($did != 'ALL') {
            $query_total_penduduk->where('das_penduduk.desa_id', '=', $did);
        }
        $total_penduduk         = $query_total_penduduk->count();
        $data['total_penduduk'] = number_format($total_penduduk);

        // Get Total Lakilaki
        $query_total_lakilaki = DB::table('das_penduduk')
            // ->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
            ->where('das_penduduk.kecamatan_id', '=', $kid)
            ->where('sex', '=', 1)
            ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
        if ($did != 'ALL') {
            $query_total_lakilaki->where('das_penduduk.desa_id', '=', $did);
        }
        $total_lakilaki         = $query_total_lakilaki->count();
        $data['total_lakilaki'] = number_format($total_lakilaki);

        // Get Total Perempuan
        $query_total_perempuan = DB::table('das_penduduk')
            //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
            ->where('das_penduduk.kecamatan_id', '=', $kid)
            ->where('sex', '=', 2)
            //->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
            ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
        if ($did != 'ALL') {
            $query_total_perempuan->where('das_penduduk.desa_id', '=', $did);
        }
        $total_perempuan         = $query_total_perempuan->count();
        $data['total_perempuan'] = number_format($total_perempuan);

        // Get Total Disabilitas
        $query_total_disabilitas = DB::table('das_penduduk')
            //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
            ->where('das_penduduk.kecamatan_id', '=', $kid)
            ->where('cacat_id', '<>', 7)
            //->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
            ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
        if ($did != 'ALL') {
            $query_total_disabilitas->where('das_penduduk.desa_id', '=', $did);
        }
        $total_disabilitas         = $query_total_disabilitas->count();
        $data['total_disabilitas'] = number_format($total_disabilitas);

        if ($total_penduduk == 0) {
            $data['ktp_wajib']            = 0;
            $data['ktp_terpenuhi']        = 0;
            $data['ktp_persen_terpenuhi'] = 0;

            $data['akta_terpenuhi']        = 0;
            $data['akta_persen_terpenuhi'] = 0;

            $data['aktanikah_wajib']            = 0;
            $data['aktanikah_terpenuhi']        = 0;
            $data['aktanikah_persen_terpenuhi'] = 0;
        } else {
            // Get Data KTP Penduduk Terpenuhi
            $query_ktp_wajib = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->where('warga_negara_id', 1) // WNI
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'ALL') {
                $query_ktp_wajib->where('das_penduduk.desa_id', '=', $did);
            }
            $query_ktp_wajib->whereRaw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(das_penduduk.tanggal_lahir)), \'%Y\')+0 > ? ', 17) // Di atas 17 Tahun
            ->orWhere('das_penduduk.status_kawin', '<>', 1);
            $ktp_wajib = $query_ktp_wajib->count();

            $query_ktp_terpenuhi = DB::table('das_penduduk')
                ->where('warga_negara_id', 1) // WNI
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'ALL') {
                $query_ktp_terpenuhi->where('das_penduduk.desa_id', '=', $did);
            }
            $query_ktp_terpenuhi->where('ktp_el', '=', 1);
            $ktp_terpenuhi        = $query_ktp_terpenuhi->count();
            $ktp_persen_terpenuhi = ($ktp_wajib - $ktp_terpenuhi) / $ktp_wajib * 100;

            $data['ktp_wajib']            = number_format($ktp_wajib);
            $data['ktp_terpenuhi']        = number_format($ktp_terpenuhi);
            $data['ktp_persen_terpenuhi'] = number_format($ktp_persen_terpenuhi);

            // Get Data Akta Penduduk Terpenuhi
            $query_akta_terpenuhi = DB::table('das_penduduk')
               // ->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->where('das_penduduk.akta_lahir', '<>', null)
                ->where('das_penduduk.akta_lahir', '<>', ' ')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
               // ->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'ALL') {
                $query_akta_terpenuhi->where('das_penduduk.desa_id', '=', $did);
            }
            $akta_terpenuhi                = $query_akta_terpenuhi->count();
            $akta_persen_terpenuhi         = ($total_penduduk - $akta_terpenuhi) / $total_penduduk * 100;
            $data['akta_terpenuhi']        = number_format($akta_terpenuhi);
            $data['akta_persen_terpenuhi'] = number_format($akta_persen_terpenuhi);

            // Get Data Akta Nikah Penduduk Terpenuhi
            $query_aktanikah_wajib = DB::table('das_penduduk')
                ->where('warga_negara_id', 1) // WNI
                ->where('agama_id', '<>', 1)
                ->where('status_kawin', '<>', 1)
                ->where('kecamatan_id', '=', $kid)
                //->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'ALL') {
                $query_aktanikah_wajib->where('desa_id', '=', $did);
            }
            $aktanikah_wajib = $query_aktanikah_wajib->count();

            $query_aktanikah_terpenuhi = DB::table('das_penduduk')
                ->where('das_penduduk.warga_negara_id', 1) // WNI
                ->where('das_penduduk.agama_id', '<>', 1)
                ->where('das_penduduk.status_kawin', '<>', 1)
                ->where('das_penduduk.akta_perkawinan', '<>', null)
                ->where('das_penduduk.akta_perkawinan', '<>', ' ')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'ALL') {
                $query_aktanikah_terpenuhi->where('das_penduduk.desa_id', '=', $did);
            }
            $aktanikah_terpenuhi                = $query_aktanikah_terpenuhi->count();
            $data['aktanikah_wajib']            = number_format(0);
            $data['aktanikah_terpenuhi']        = number_format(0);
            $data['aktanikah_persen_terpenuhi'] = number_format(0);
            if ($aktanikah_wajib != 0) {
                $aktanikah_persen_terpenuhi         = ($aktanikah_terpenuhi / $aktanikah_wajib) * 100;
                $data['aktanikah_wajib']            = number_format($aktanikah_wajib);
                $data['aktanikah_terpenuhi']        = number_format($aktanikah_terpenuhi);
                $data['aktanikah_persen_terpenuhi'] = number_format($aktanikah_persen_terpenuhi);
            }
        }

        return $data;
    }

    public function getChartPenduduk()
    {
        $kid  = request('kid');
        $did  = request('did');
        $year = request('y');

        // Data Grafik Pertumbuhan
        $data = [];
        foreach (array_sort($this->years_list()) as $yearls) {
            $query_result_laki = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->select('das_keluarga.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                ->where('das_penduduk.sex', '=', 1)
               //->whereRaw('year(das_keluarga.tgl_daftar) = ?', $yearls);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $yearls);
            if ($did != 'ALL') {
                $query_result_laki->where('das_penduduk.desa_id', '=', $did);
            }

            $query_result_perempuan = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->select('das_keluarga.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                ->where('das_penduduk.sex', '=', 2)
                //->whereRaw('year(das_keluarga.tgl_daftar) = ?', $yearls);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $yearls);
            if ($did != 'ALL') {
                $query_result_perempuan->where('das_penduduk.desa_id', '=', $did);
            }
            $data[] = ['year' => $yearls, 'value_lk' => $query_result_laki->count(), 'value_pr' => $query_result_perempuan->count()];
        }
        return $data;
    }

    public function getChartPendudukUsia()
    {
        $kid  = request('kid');
        $did  = request('did');
        $year = request('y');

        // Data Grafik Kategori Usia
        $categories = DB::table('ref_umur')->orderBy('ref_umur.dari')->where('status', '=', 1)->get();
        $colors     = [7 => '#09a8ff', 6 => '#09bcff', 5 => '#09d1ff', 4 => '#09e5ff', 3 => '#09faff', 2 => '#09fff0', 1 => '#09ffdc'];
        $data       = [];
        foreach ($categories as $umur) {
            $query_total = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar) = ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year)
                ->whereRaw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(das_penduduk.tanggal_lahir)), \'%Y\')+0 > ? ', $umur->dari)
                ->whereRaw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggal_lahir)), \'%Y\')+0 <= ?', $umur->sampai);
            if ($did != 'ALL') {
                $query_total->where('das_penduduk.desa_id', '=', $did);
            }
            $total  = $query_total->count();
            $data[] = ['umur' => ucfirst(strtolower($umur->nama)) . ' (' . $umur->dari . ' - ' . $umur->sampai . ' tahun)', 'value' => $total, 'color' => $colors[$umur->id]];
        }

        return $data;
    }

    public function getChartPendudukPendidikan()
    {
        $kid  = request('kid');
        $did  = request('did');
        $year = request('y');

        // Grafik Data Pendidikan
        $data_pendidikan = [];
        if ($year == 'ALL') {
            foreach ($this->years_list() as $yearl) {
                // SD
                $query_total_sd = DB::table('das_penduduk')
                    //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                    ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                    ->where('das_penduduk.kecamatan_id', '=', $kid)
                    //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $yearl)
                    ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $yearl)
                    ->where('das_penduduk.pendidikan_kk_id', '=', 3);
                if ($did != 'ALL') {
                    $query_total_sd->where('das_penduduk.desa_id', '=', $did);
                }
                $total_sd = $query_total_sd->count();

                // SMP
                $query_total_sltp = DB::table('das_penduduk')
                    //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                    ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                    ->where('das_penduduk.kecamatan_id', '=', $kid)
                    //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $yearl)
                    ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $yearl)
                    ->where('das_penduduk.pendidikan_kk_id', '=', 4);
                if ($did != 'ALL') {
                    $query_total_sltp->where('das_penduduk.desa_id', '=', $did);
                }
                $total_sltp = $query_total_sltp->count();

                //SMA
                $query_total_slta = DB::table('das_penduduk')
                    //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                    ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                    ->where('das_penduduk.kecamatan_id', '=', $kid)
                    //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $yearl)
                    ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $yearl)
                    ->where('das_penduduk.pendidikan_kk_id', '=', 5);
                if ($did != 'ALL') {
                    $query_total_slta->where('das_penduduk.desa_id', '=', $did);
                }
                $total_slta = $query_total_slta->count();

                // DIPLOMA
                $query_total_diploma = DB::table('das_penduduk')
                    //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                    ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                    ->where('das_penduduk.kecamatan_id', '=', $kid)
                    //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $yearl)
                    ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $yearl)
                    ->whereRaw('(das_penduduk.pendidikan_kk_id = 6 or das_penduduk.pendidikan_kk_id = 7)');
                if ($did != 'ALL') {
                    $query_total_diploma->where('das_penduduk.desa_id', '=', $did);
                }
                $total_diploma = $query_total_diploma->count();

                // SARJANA
                $query_total_sarjana = DB::table('das_penduduk')
                    //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                    ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                    ->where('das_penduduk.kecamatan_id', '=', $kid)
                    //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $yearl)
                    ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $yearl)
                    ->whereRaw('(das_penduduk.pendidikan_kk_id = 8 or das_penduduk.pendidikan_kk_id = 9 or das_penduduk.pendidikan_kk_id = 10)');
                if ($did != 'ALL') {
                    $query_total_sarjana->where('das_penduduk.desa_id', '=', $did);
                }
                $total_sarjana = $query_total_sarjana->count();

                $data_pendidikan[] = [
                    'year'    => $yearl,
                    'SD'      => $total_sd,
                    'SLTP'    => $total_sltp,
                    'SLTA'    => $total_slta,
                    'DIPLOMA' => $total_diploma,
                    'SARJANA' => $total_sarjana,
                ];
            }
        } else {
            // SD
            $query_total_sd = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year)
                ->where('das_penduduk.pendidikan_kk_id', '=', 3);
            if ($did != 'ALL') {
                $query_total_sd->where('das_penduduk.desa_id', '=', $did);
            }
            $total_sd = $query_total_sd->count();

            // SMP
            $query_total_sltp = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year)
                ->where('das_penduduk.pendidikan_kk_id', '=', 4);
            if ($did != 'ALL') {
                $query_total_sltp->where('das_penduduk.desa_id', '=', $did);
            }
            $total_sltp = $query_total_sltp->count();

            //SMA
            $query_total_slta = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year)
                ->where('das_penduduk.pendidikan_kk_id', '=', 5);
            if ($did != 'ALL') {
                $query_total_slta->where('das_penduduk.desa_id', '=', $did);
            }
            $total_slta = $query_total_slta->count();

            // DIPLOMA
            $query_total_diploma = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year)
                ->whereRaw('(das_penduduk.pendidikan_kk_id = 6 or das_penduduk.pendidikan_kk_id = 7)');
            if ($did != 'ALL') {
                $query_total_diploma->where('das_penduduk.desa_id', '=', $did);
            }
            $total_diploma = $query_total_diploma->count();

            // SARJANA
            $query_total_sarjana = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year)
                ->whereRaw('(das_penduduk.pendidikan_kk_id = 8 or das_penduduk.pendidikan_kk_id = 9 or das_penduduk.pendidikan_kk_id = 10)');
            if ($did != 'ALL') {
                $query_total_sarjana->where('das_penduduk.desa_id', '=', $did);
            }
            $total_sarjana = $query_total_sarjana->count();

            $data_pendidikan[] = [
                'year'    => $year,
                'SD'      => $total_sd,
                'SLTP'    => $total_sltp,
                'SLTA'    => $total_slta,
                'DIPLOMA' => $total_diploma,
                'SARJANA' => $total_sarjana,
            ];
        }

        return $data_pendidikan;
    }

    public function getChartPendudukGolDarah()
    {
        $kid  = request('kid');
        $did  = request('did');
        $year = request('y');

        // Data Chart Penduduk By Golongan Darah
        $data           = [];
        $golongan_darah = DB::table('ref_golongan_darah')->orderBy('id')->get();
        $colors         = [1 => '#f97d7d', 2 => '#f86565', 3 => '#f74d4d', 4 => '#f63434', 13 => '#f51c1c'];
        foreach ($golongan_darah as $val) {
            $query_total = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($val->id != 13) {
                $query_total->where('das_penduduk.golongan_darah_id', '=', $val->id);
            } else {
                $query_total->whereRaw('das_penduduk.golongan_darah_id = 13 or das_penduduk.golongan_darah_id is null');
            }

            if ($did != 'ALL') {
                $query_total->where('das_penduduk.desa_id', '=', $did);
            }
            $total  = $query_total->count();
            $data[] = ['blod_type' => $val->nama, 'total' => $total, 'color' => $colors[$val->id]];
        }

        return $data;
    }

    public function getChartPendudukKawin()
    {
        $kid  = request('kid');
        $did  = request('did');
        $year = request('y');

        // Data Chart Penduduk By Status Perkawinan
        $data         = [];
        $status_kawin = DB::table('ref_kawin')->orderBy('id')->get();
        $colors       = [1 => '#d365f8', 2 => '#c534f6', 3 => '#b40aed', 4 => '#8f08bc'];
        foreach ($status_kawin as $val) {
            $query_total = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year)
                ->where('das_penduduk.status_kawin', '=', $val->id);

            if ($did != 'ALL') {
                $query_total->where('das_penduduk.desa_id', '=', $did);
            }
            $total  = $query_total->count();
            $data[] = ['status' => ucfirst(strtolower($val->nama)), 'total' => $total, 'color' => $colors[$val->id]];
        }

        return $data;
    }

    public function getChartPendudukAgama()
    {
        $kid  = request('kid');
        $did  = request('did');
        $year = request('y');

        // Data Chart Penduduk By Aama
        $data   = [];
        $agama  = DB::table('ref_agama')->orderBy('id')->get();
        $colors = [1 => '#dcaf1e', 2 => '#dc9f1e', 3 => '#dc8f1e', 4 => '#dc7f1e', 5 => '#dc6f1e', 6 => '#dc5f1e', 7 => '#dc4f1e'];
        foreach ($agama as $val) {
            $query_total = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->leftJoin('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->where('das_penduduk.agama_id', '=', $val->id)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);

            if ($did != 'ALL') {
                $query_total->where('das_penduduk.desa_id', '=', $did);
            }
            $total  = $query_total->count();
            $data[] = ['religion' => ucfirst(strtolower($val->nama)), 'total' => $total, 'color' => $colors[$val->id]];
        }

        return $data;
    }

    public function getChartPendudukKelamin()
    {
        $kid  = request('kid');
        $did  = request('did');
        $year = request('y');

        // Data Chart Penduduk By Jenis Kelamin
        $data    = [];
        $kelamin = [
            [
                'id'   => 1,
                'nama' => 'Laki-Laki',
            ],
            [
                'id'   => 2,
                'nama' => 'Perempuan',
            ],
        ];
        foreach ($kelamin as $val) {
            $query_total = DB::table('das_penduduk')
                //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
                ->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year)
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year)
                ->where('das_penduduk.sex', '=', $val['id']);

            if ($did != 'ALL') {
                $query_total->where('das_penduduk.desa_id', '=', $did);
            }
            $total  = $query_total->count();
            $data[] = ['sex' => $val['nama'], 'total' => $total, 'color' => '#' . random_color()];
        }

        return $data;
    }

    public function getDataPenduduk()
    {
        $type = request('t');
        $kid  = request('kid');
        $did  = request('did');
        $year = request('year');

        $query = DB::table('das_penduduk')
            //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
            ->join('ref_pendidikan_kk', 'das_penduduk.pendidikan_kk_id', '=', 'ref_pendidikan_kk.id')
            ->join('ref_kawin', 'das_penduduk.status_kawin', '=', 'ref_kawin.id')
            ->join('ref_pekerjaan', 'das_penduduk.pekerjaan_id', '=', 'ref_pekerjaan.id')
            ->selectRaw('das_penduduk.id, das_penduduk.nik, das_penduduk.nama, das_penduduk.no_kk, das_penduduk.alamat_sekarang as alamat,
            ref_pendidikan_kk.nama as pendidikan,
            das_penduduk.tanggal_lahir, ref_kawin.nama as status_kawin, ref_pekerjaan.nama as pekerjaan, das_penduduk.foto');
        if ($type == 'C') {
            $query->where('das_penduduk.kecamatan_id', '=', $kid)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'ALL') {
                $query->where('das_penduduk.desa_id', '=', $did);
            }
        }
        if ($type == 'L') {
            $query->where('das_penduduk.kecamatan_id', '=', $kid)
                ->where('das_penduduk.sex', '=', 1)
               // ->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'ALL') {
                $query->where('das_penduduk.desa_id', '=', $did);
            }
        }
        if ($type == 'P') {
            $query->where('das_penduduk.kecamatan_id', '=', $kid)
                ->where('das_penduduk.sex', '=', 2)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'ALL') {
                $query->where('das_penduduk.desa_id', '=', $did);
            }
        }
        if ($type == 'D') {
            $query->where('das_penduduk.kecamatan_id', '=', $kid)
                ->where('das_penduduk.cacat_id', '<>', 7)
                //->whereRaw('year(das_keluarga.tgl_daftar)= ?', $year);
                ->whereRaw('YEAR(das_penduduk.created_at) <= ?', $year);
            if ($did != 'ALL') {
                $query->where('das_penduduk.desa_id', '=', $did);
            }
        }

        return DataTables::of($query)
            ->addColumn('tanggal_lahir', function ($row) {
                return convert_born_date_to_age($row->tanggal_lahir);
            })->make(true);
    }
}
