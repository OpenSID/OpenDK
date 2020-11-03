<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profil;
use Illuminate\Support\Facades\DB;

use function env;
use function number_format;
use function request;
use function view;
use function years_list;

class DashboardController extends Controller
{
    /**
     * Menampilkan Data Kesehatan
     **/
    public function showKesehatan()
    {
        $data['page_title']       = 'Kesehatan';
        $data['page_description'] = 'Data Kesehatan';
        $defaultProfil            = env('KD_DEFAULT_PROFIL', '1');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        $data['list_desa']        = DB::table('ref_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();

        return view('dashboard.kesehatan.show_kesehatan')->with($data);
    }

    /**
     * Menampilkan Data Program Bantuan
     **/
    public function showProgramBantuan()
    {
        $data['page_title']       = 'Program Bantuan';
        $data['page_description'] = 'Data Program Bantuan';
        $defaultProfil            = env('KD_DEFAULT_PROFIL', '1');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        $data['list_desa']        = DB::table('ref_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();

        return view('dashboard.programBantuan')->with($data);
    }

    /**
     * Menampilkan Data Anggaran Dan realisasi Kecamatan
     **/
    public function showAnggaranDanRealisasi()
    {
        $data['page_title']       = 'Anggaran Dan realisasi Kecamatan';
        $data['page_description'] = 'Data Anggaran Dan realisasi Kecamatan';
        $defaultProfil            = env('KD_DEFAULT_PROFIL', '1');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        $data['list_desa']        = DB::table('ref_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();

        return view('dashboard.anggaranDanRealisasi')->with($data);
    }

    /**
     * Menampilkan Data Anggaran Dan realisasi Kecamatan
     **/
    public function showAnggaranDesa()
    {
        $data['page_title']       = 'APBDes';
        $data['page_description'] = 'Kecamatan';
        $defaultProfil            = env('KD_DEFAULT_PROFIL', '1');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        $data['list_desa']        = DB::table('ref_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();

        return view('dashboard.anggaranDesa')->with($data);
    }

    public function getChartAnggaranRealisasi()
    {
        $kid  = request('kid');
        $did  = request('did');
        $year = request('y');

        // Grafik Data Pendidikan
        $data_pendidikan = [];
        if ($year == 'ALL') {
            $data_pendidikan['sum']   = [
                'total_belanja'                     => number_format(7077753880),
                'total_belanja_persen'              => number_format(100),
                'selisih_anggaran_realisasi'        => number_format(0),
                'selisih_anggaran_realisasi_persen' => number_format(0),
                'belanja_pegawai'                   => number_format(511275000),
                'belanja_pegawai_persen'            => number_format(7.22),
                'belanja_barang_jasa'               => number_format(1478620760),
                'belanja_barang_jasa_persen'        => number_format(20.89),
                'belanja_modal'                     => number_format(85333146),
                'belanja_modal_persen'              => number_format(1.21),
                'belanja_tidak_langsung'            => number_format(5002524974),
                'belanja_tidak_langsung_persen'     => number_format(70.68),
            ];
            $data_pendidikan['chart'] = [
                [
                    'anggaran' => 'Belanja Pegawai',
                    'value'    => 7.22,
                ],
                [
                    'anggaran' => 'Belanja Barang dan Jasa',
                    'value'    => 20.89,
                ],
                [
                    'anggaran' => 'Belanja Modal',
                    'value'    => 1.21,
                ],
                [
                    'anggaran' => 'Belanja Tidak Langsung',
                    'value'    => 70.68,
                ],
            ];
        } else {
            $data = [
                '2016' => [
                    'sum'   => [
                        'total_belanja'                     => number_format(2359257351),
                        'total_belanja_persen'              => number_format(100),
                        'selisih_anggaran_realisasi'        => number_format(0),
                        'selisih_anggaran_realisasi_persen' => number_format(0),
                        'belanja_pegawai'                   => number_format(207625000),
                        'belanja_pegawai_persen'            => number_format(8.80),
                        'belanja_barang_jasa'               => number_format(509258519),
                        'belanja_barang_jasa_persen'        => number_format(21.59),
                        'belanja_modal'                     => number_format(8982250),
                        'belanja_modal_persen'              => number_format(0.38),
                        'belanja_tidak_langsung'            => number_format(1633391582),
                        'belanja_tidak_langsung_persen'     => number_format(69.23),
                    ],
                    'chart' => [
                        [
                            'anggaran' => 'Belanja Pegawai',
                            'value'    => 8.80,
                        ],
                        [
                            'anggaran' => 'Belanja Barang dan Jasa',
                            'value'    => 21.59,
                        ],
                        [
                            'anggaran' => 'Belanja Modal',
                            'value'    => 0.38,
                        ],
                        [
                            'anggaran' => 'Belanja Tidak Langsung',
                            'value'    => 69.23,
                        ],
                    ],
                ],
                '2017' => [
                    'sum'   => [
                        'total_belanja'                     => number_format(2557755861),
                        'total_belanja_persen'              => number_format(100),
                        'selisih_anggaran_realisasi'        => number_format(0),
                        'selisih_anggaran_realisasi_persen' => number_format(0),
                        'belanja_pegawai'                   => number_format(143025000),
                        'belanja_pegawai_persen'            => number_format(5.59),
                        'belanja_barang_jasa'               => number_format(730261765),
                        'belanja_barang_jasa_persen'        => number_format(28.55),
                        'belanja_modal'                     => number_format(24263235),
                        'belanja_modal_persen'              => number_format(0.95),
                        'belanja_tidak_langsung'            => number_format(1660205861),
                        'belanja_tidak_langsung_persen'     => number_format(64.91),
                    ],
                    'chart' => [
                        [
                            'anggaran' => 'Belanja Pegawai',
                            'value'    => 5.59,
                        ],
                        [
                            'anggaran' => 'Belanja Barang dan Jasa',
                            'value'    => 28.55,
                        ],
                        [
                            'anggaran' => 'Belanja Modal',
                            'value'    => 0.95,
                        ],
                        [
                            'anggaran' => 'Belanja Tidak Langsung',
                            'value'    => 64.91,
                        ],
                    ],
                ],
                '2018' => [
                    'sum'   => [
                        'total_belanja'                     => number_format(2160740668),
                        'total_belanja_persen'              => number_format(100),
                        'selisih_anggaran_realisasi'        => number_format(0),
                        'selisih_anggaran_realisasi_persen' => number_format(0),
                        'belanja_pegawai'                   => number_format(160625000),
                        'belanja_pegawai_persen'            => number_format(7.43),
                        'belanja_barang_jasa'               => number_format(239100476),
                        'belanja_barang_jasa_persen'        => number_format(11.07),
                        'belanja_modal'                     => number_format(52087661),
                        'belanja_modal_persen'              => number_format(2.41),
                        'belanja_tidak_langsung'            => number_format(1708927531),
                        'belanja_tidak_langsung_persen'     => number_format(79.09),
                    ],
                    'chart' => [
                        [
                            'anggaran' => 'Belanja Pegawai',
                            'value'    => 7.43,
                        ],
                        [
                            'anggaran' => 'Belanja Barang dan Jasa',
                            'value'    => 11.07,
                        ],
                        [
                            'anggaran' => 'Belanja Modal',
                            'value'    => 2.41,
                        ],
                        [
                            'anggaran' => 'Belanja Tidak Langsung',
                            'value'    => 79.09,
                        ],
                    ],
                ],
            ];

            $data_pendidikan = $data[$year];
        }

        return $data_pendidikan;
    }
}
