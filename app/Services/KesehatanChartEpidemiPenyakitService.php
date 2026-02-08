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
                    $semesterIds = explode(',', $this->getIdsSemester($key));
                    $query_total = DB::table('das_epidemi_penyakit')
                        //->join('ref_penyakit', 'das_epidemi_penyakit.penyakit_id', '=', 'ref_penyakit.id')
                        ->where('das_epidemi_penyakit.kecamatan_id', '=', get_kode_kecamatan())
                        ->whereIn('das_epidemi_penyakit.bulan', $semesterIds)
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