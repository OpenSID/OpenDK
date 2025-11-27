<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class KesehatanChartEpidemiPenyakitService
{
    public $nama_kuartal = ['q1' => 'Kuartal 1', 'q2' => 'Kuartal 2', 'q3' => 'Kuartal 3', 'q4' => 'Kuartal 4'];

    public function chart($did, $year)
    {
        $data = [];

        // Grafik Data Kesehatan Cakupan Imunisasi
        $data_kesehatan = [];
        if ($year == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $query = DB::table('das_epidemi_penyakit')
                    ->where('tahun', '=', $yearl);
                if ($did != 'Semua') {
                    $query->where('desa_id', '=', $did);
                }

                $data_kesehatan[] = [
                    'year' => $yearl,
                    'jumlah' => $query->sum('jumlah_penderita'),
                ];
            }
        } else {
            $datas = [];

            foreach (semester() as $key => $val) {
                $penyakit = DB::table('ref_penyakit')->get();
                $temp = [];
                foreach ($penyakit as $value) {
                    $query_total = DB::table('das_epidemi_penyakit')
                        //->join('ref_penyakit', 'das_epidemi_penyakit.penyakit_id', '=', 'ref_penyakit.id')
                        ->where('das_epidemi_penyakit.kecamatan_id', '=', get_kode_kecamatan())
                        ->whereRaw('das_epidemi_penyakit.bulan in ('.$this->getIdsSemester($key).')')
                        ->where('das_epidemi_penyakit.tahun', $year)
                        ->where('das_epidemi_penyakit.penyakit_id', $value->id);

                    if ($did != 'Semua') {
                        $query_total->where('das_epidemi_penyakit.desa_id', '=', $did);
                    }
                    $total = $query_total->sum('das_epidemi_penyakit.jumlah_penderita');
                    $temp['penyakit'.$value->id] = $total;
                }
                $temp['year'] = 'Semester '.$key;
                $datas[] = $temp;
            }

            $data_kesehatan = $datas;
        }

        // Data Tabel Cakupan Imunisasi
        $tabel_kesehatan = [];

        // Kuartal & Detail Per Desa
        /*if($year!= 'Semua' && $did== 'Semua'){
            $data_tabel = array();
            // Semester

            foreach(semester() as $key=>$semester){
                $query = DB::table('das_epidemi_penyakit')
                    ->selectRaw('ref_penyakit.nama as penyakit, sum(das_epidemi_penyakit.jumlah_penderita)')
                    ->join('ref_penyakit', 'das_epidemi_penyakit.penyakit_id', '=', 'ref_penyakit.id')
                    ->whereRaw('das_epidemi_penyakit.bulan in ('.$this->getIdsSemester($key).')')
                    ->where('das_epidemi_penyakit.tahun', $year);
                $data_tabel['quartal'][$key] = array(
                    'penyakit'  => '',
                    'jumlah_penderita' =>'' ,
                );
            }

            // Detail Desa
            foreach(kuartal_bulan() as $key=>$semester){
                $query = DB::table('das_imunisasi')
                    ->join('das_data_desa', 'das_imunisasi.desa_id', '=', 'das_data_desa.desa_id')
                    ->selectRaw('das_data_desa.nama, sum(das_imunisasi.cakupan_imunisasi) as cakupan_imunisasi')
                    ->whereRaw('das_imunisasi.bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('das_imunisasi.tahun', $year)
                    ->groupBy('das_data_desa.nama')->get();
                $data_tabel['desa'][$key] = $query;
            }

            $tabel_kesehatan = view('dashboard.kesehatan.tabel_penyakit_1', compact('data_tabel'))->render();
            //$tabel_kesehatan = $data_tabel;

        }elseif($year != 'Semua' && $did != 'Semua'){
            $data_tabel = array();
            foreach(kuartal_bulan() as $key=>$semester){
                $query = DB::table('das_imunisasi')
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year)
                    ->where('desa_id', $did);
                $data_tabel['quartal'][$key] = array(
                    'cakupan_imunisasi' => $query->sum('cakupan_imunisasi'),
                );
            }

            //$tabel_kesehatan = $data_tabel;
            $tabel_kesehatan = view('dashboard.kesehatan.tabel_penyakit_2', compact('data_tabel'))->render();
        }*/
        return [
            'grafik' => $data_kesehatan,
            'tabel' => $tabel_kesehatan,
        ];
    }

    private function getIdsSemester($sm)
    {
        $semester = semester()[$sm];
        $ids = '';
        foreach ($semester as $key => $val) {
            $ids .= $key.',';
        }

        return rtrim($ids, ',');
    }
}