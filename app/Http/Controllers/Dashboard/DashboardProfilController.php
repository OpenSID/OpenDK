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
use function years_list;

class DashboardProfilController extends Controller
{
    /**
     * Menampilkan Data Profil Kecamatan
     **/

    public function index()
    {
        Counter::count('dashboard.profil');

        $defaultProfil = config('app.default_profile');

        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Profil';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }

        return view('pages.post_show', compact('page_title', 'page_description', 'profil', 'defaultProfil', 'dokumen'));
    }

    public function showKependudukanPartial()
    {
        $data['page_title']       = 'Kependudukan';
        $data['page_description'] = 'Statistik Kependudukan';
        $defaultProfil            = env('DAS_DEFAULT_PROFIL', '1');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();

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
            ->where('das_penduduk.tahun', $year);
        if ($did != 'ALL') {
            $query_total_penduduk->where('das_penduduk.desa_id', '=', $did);
        }
        $total_penduduk         = $query_total_penduduk->count();
        $data['total_penduduk'] = number_format($total_penduduk);

        // Get Total Lakilaki
        /*$query_total_lakilaki = DB::table('das_penduduk')
            ->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
            ->where('das_penduduk.kecamatan_id', '=', $kid)
            ->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
        if ($did != 'ALL') {
            $query_total_lakilaki->where('das_penduduk.desa_id', '=', $did);
        }*/
        $total_lakilaki         = $query_total_penduduk->where('sex', '=', 1)->count();
        $data['total_lakilaki'] = number_format($total_lakilaki);

        // Get Total Perempuan
        $query_total_perempuan = DB::table('das_penduduk')
            //->join('das_keluarga', 'das_penduduk.no_kk', '=', 'das_keluarga.no_kk')
            ->where('das_penduduk.kecamatan_id', '=', $kid)
            ->where('sex', '=', 2)
            //->whereRaw('YEAR(das_keluarga.tgl_daftar) = ?', $year);
            ->where('das_penduduk.tahun', $year);
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
            ->where('das_penduduk.tahun', $year);
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
                ->where('das_penduduk.tahun', $year);
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
                ->where('das_penduduk.tahun', $year);
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
               ->where('das_penduduk.tahun', $year);
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
                ->where('tahun', $year);
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
                ->where('das_penduduk.tahun', $year);
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

    public function LetakGeografis()
    {
        $defaultProfil = config('app.default_profile');

        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $page_title = 'Letak Geografis';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }

        return view('pages.profil.letakgeografis', compact('page_title', 'page_description', 'profil', 'defaultProfil'));
    }

    public function StrukturPemerintahan()
    {
        $defaultProfil = config('app.default_profile');
        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Struktur Pemerintahan';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }
        return view('pages.profil.strukturpemerintahan', compact('page_title', 'page_description', 'profil'));
    }

    public function Kependudukan()
    {
        Counter::count('profil.kependudukan');

        $data['page_title']       = 'Kependudukan';
        $data['page_description'] = 'Statistik Kependudukan';
        $defaultProfil            = config('app.default_profile');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        $data['list_desa']        = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();

        $data = array_merge($data, $this->createDashboardKependudukan($defaultProfil, 'ALL', date('Y')));

        return view('pages.kependudukan.show_kependudukan')->with($data);
    }

    public function VisiMisi()
    {
        $defaultProfil = config('app.default_profile');
        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Visi dan Misi';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }
        return view('pages.profil.visimisi', compact('page_title', 'page_description', 'profil'));
    }

    public function sejarah()
    {
        $defaultProfil = config('app.default_profile');

        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();
        $page_title = 'Sejarah';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }
        return view('pages.profil.sejarah', compact('page_title', 'page_description', 'profil'));
    }

    public function showProfile()
    {
        Counter::count('dashboard.profil');

        $defaultProfil = config('app.default_profile');

        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Profil';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }

        return view('pages.profil.show_profil', compact('page_title', 'page_description', 'profil', 'defaultProfil', 'dokumen'));
    }
}
