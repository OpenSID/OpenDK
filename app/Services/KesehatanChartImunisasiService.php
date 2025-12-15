<?php

namespace App\Services;

use App\Models\Imunisasi;
use App\Models\DataDesa;
use Illuminate\Support\Facades\DB;

class KesehatanChartImunisasiService
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
                $query = Imunisasi::query()
                    ->where('tahun', '=', $yearl);
                if ($did != 'Semua') {
                    $query->where('desa_id', '=', $did);
                }

                $data_kesehatan[] = [
                    'year' => $yearl,
                    'cakupan_imunisasi' => $query->sum('cakupan_imunisasi'),
                ];
            }
        } else {
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = Imunisasi::query()
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $query->where('desa_id', '=', $did);
                }
                $data_tabel[] = [
                    'year' => $this->nama_kuartal[$key],
                    'cakupan_imunisasi' => $query->sum('cakupan_imunisasi'),
                ];
            }

            $data_kesehatan = $data_tabel;
        }

        // Data Tabel Cakupan Imunisasi
        $tabel_kesehatan = [];

        // Kuartal & Detail Per Desa
        if ($year != 'Semua' && $did == 'Semua') {
            $data_tabel = [];
            // Quartal
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = Imunisasi::query()
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year);
                $data_tabel['quartal'][$key] = [
                    'cakupan_imunisasi' => $query->sum('cakupan_imunisasi'),
                ];
            }

            // Detail Desa
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = Imunisasi::query()
                    ->join('das_data_desa', 'das_imunisasi.desa_id', '=', 'das_data_desa.desa_id')
                    ->selectRaw('das_data_desa.nama, sum(das_imunisasi.cakupan_imunisasi) as cakupan_imunisasi')
                    ->whereRaw('das_imunisasi.bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('das_imunisasi.tahun', $year)
                    ->groupBy('das_data_desa.nama')->get();
                $data_tabel['desa'][$key] = $query;
            }

            $tabel_kesehatan = view('pages.kesehatan.tabel_imunisasi_1', compact('data_tabel'))->render();
            //$tabel_kesehatan = $data_tabel;
        } elseif ($year != 'Semua' && $did != 'Semua') {
            $data_tabel = [];
            foreach (kuartal_bulan() as $key => $kuartal) {
                $query = Imunisasi::query()
                    ->whereRaw('bulan in ('.$this->getIdsQuartal($key).')')
                    ->where('tahun', $year)
                    ->where('desa_id', $did);
                $data_tabel['quartal'][$key] = [
                    'cakupan_imunisasi' => $query->sum('cakupan_imunisasi'),
                ];
            }

            //$tabel_kesehatan = $data_tabel;
            $tabel_kesehatan = view('pages.kesehatan.tabel_imunisasi_2', compact('data_tabel'))->render();
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