<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class KesehatanChartAKIAKBService
{
    public $nama_kuartal = ['q1' => 'Kuartal 1', 'q2' => 'Kuartal 2', 'q3' => 'Kuartal 3', 'q4' => 'Kuartal 4'];

    public function chart($did, $year)
    {
        $data = [];

        // Grafik Data Kesehatan AKI & AKB
        $data_kesehatan = [];
        if ($year == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $query_aki = DB::table('das_akib')
                    ->where('tahun', '=', $yearl);
                if ($did != 'Semua') {
                    $query_aki->where('desa_id', '=', $did);
                }
                $aki = $query_aki->sum('aki');
                $akb = $query_aki->sum('akb');

                $data_kesehatan[] = [
                    'year' => $yearl,
                    'aki' => $aki,
                    'akb' => $akb,
                ];
            }
        } else {
            $data_tabel = [];
            // Quartal
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = DB::table('das_akib')
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $query->where('desa_id', '=', $did);
                }
                $data_tabel[] = [
                    'year' => $this->nama_kuartal[$key],
                    'aki' => $query->sum('aki'),
                    'akb' => $query->sum('akb'),
                ];
            }

            $data_kesehatan = $data_tabel;
        }

        // Data Tabel AKI & AKB
        $tabel_kesehatan = [];

        // Kuartal & Detail Per Desa
        if ($year != 'Semua' && $did == 'Semua') {
            $data_tabel = [];
            // Quartal
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = DB::table('das_akib')
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year);
                $data_tabel['quartal'][$key] = [
                    'aki' => $query->sum('aki'),
                    'akb' => $query->sum('akb'),
                ];
            }

            // Detail Desa
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = DB::table('das_akib')
                    ->join('das_data_desa', 'das_akib.desa_id', '=', 'das_data_desa.desa_id')
                    ->selectRaw('das_data_desa.nama, sum(das_akib.aki) as aki, sum(das_akib.akb) as akb')
                    ->whereRaw('das_akib.bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('das_akib.tahun', $year)
                    ->groupBy('das_data_desa.nama')->get();
                $data_tabel['desa'][$key] = $query;
            }

            $tabel_kesehatan = view('pages.kesehatan.tabel_akiakb_1', compact('data_tabel'))->render();
            //$tabel_kesehatan = $data_tabel;
        } elseif ($year != 'Semua' && $did != 'Semua') {
            $data_tabel = [];
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = DB::table('das_akib')
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year)
                    ->where('desa_id', $did);
                $data_tabel['quartal'][$key] = [
                    'aki' => $query->sum('aki'),
                    'akb' => $query->sum('akb'),
                ];
            }

            $tabel_kesehatan = view('pages.kesehatan.tabel_akiakb_2', compact('data_tabel'))->render();
        }

        return [
            'grafik' => $data_kesehatan,
            'tabel' => $tabel_kesehatan,
        ];
    }

    private function getIdsQuartal($q)
    {
        $quartal = kuartal_bulan()[$q];
        $ids = '';
        foreach ($quartal as $key => $val) {
            $ids .= $key.',';
        }

        return rtrim($ids, ',');
    }
}