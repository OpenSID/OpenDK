<?php

namespace App\Services;

use App\Models\ToiletSanitasi;
use App\Models\DataDesa;
use Illuminate\Support\Facades\DB;

class KesehatanChartToiletSanitasiService
{
    public $nama_kuartal = ['q1' => 'Kuartal 1', 'q2' => 'Kuartal 2', 'q3' => 'Kuartal 3', 'q4' => 'Kuartal 4'];

    public function chart($did, $year)
    {
        $data = [];

        // Grafik Data Toilet & Sanitasi
        $data_kesehatan = [];
        if ($year == 'Semua') {
            foreach (years_list() as $yearl) {
                $query = ToiletSanitasi::query()
                    ->where('tahun', '=', $yearl);
                if ($did != 'Semua') {
                    $query->where('desa_id', '=', $did);
                }

                $data_kesehatan[] = [
                    'year' => $yearl,
                    'toilet' => $query->sum('toilet'),
                    'sanitasi' => $query->sum('sanitasi'),
                ];
            }
        } else {
            $data_tabel = [];
            // Quartal
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = ToiletSanitasi::query()
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $query->where('desa_id', '=', $did);
                }
                $data_tabel[] = [
                    'year' => $this->nama_kuartal[$key],
                    'toilet' => $query->sum('toilet'),
                    'sanitasi' => $query->sum('sanitasi'),
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
                $query = ToiletSanitasi::query()
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year);
                $data_tabel['quartal'][$key] = [
                    'toilet' => $query->sum('toilet'),
                    'sanitasi' => $query->sum('sanitasi'),
                ];
            }

            // Detail Desa
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = ToiletSanitasi::query()
                    ->join('das_data_desa', 'das_toilet_sanitasi.desa_id', '=', 'das_data_desa.desa_id')
                    ->selectRaw('das_data_desa.nama, sum(das_toilet_sanitasi.toilet) as toilet, sum(das_toilet_sanitasi.sanitasi) as sanitasi')
                    ->whereRaw('das_toilet_sanitasi.bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('das_toilet_sanitasi.tahun', $year)
                    ->groupBy('das_data_desa.nama')->get();
                $data_tabel['desa'][$key] = $query;
            }

            $tabel_kesehatan = view('pages.kesehatan.tabel_sanitasi_1', compact('data_tabel'))->render();
            //$tabel_kesehatan = $data_tabel;
        } elseif ($year != 'Semua' && $did != 'Semua') {
            $data_tabel = [];
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = ToiletSanitasi::query()
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year)
                    ->where('desa_id', $did);
                $data_tabel['quartal'][$key] = [
                    'toilet' => $query->sum('toilet'),
                    'sanitasi' => $query->sum('sanitasi'),
                ];
            }

            $tabel_kesehatan = view('pages.kesehatan.tabel_sanitasi_2', compact('data_tabel'))->render();
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