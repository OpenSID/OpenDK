<?php

namespace App\Services;

use App\Models\FasilitasPAUD;
use Illuminate\Support\Facades\DB;

class PendidikanChartFasilitasPAUDService
{
    public $nama_kuartal = ['q1' => 'Kuartal 1', 'q2' => 'Kuartal 2', 'q3' => 'Kuartal 3', 'q4' => 'Kuartal 4'];

    public function chart($did, $year)
    {
        // Grafik Data Fasilitas PAUD
        $dataPendidikan = [];
        if ($year == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $queryPendidikan = FasilitasPAUD::where('tahun', '=', $yearl);
                if ($did != 'Semua') {
                    $queryPendidikan->where('desa_id', '=', $did);
                }

                $dataPendidikan[] = [
                    'year' => $yearl,
                    'jumlah_paud' => $queryPendidikan->sum('jumlah_paud'),
                    'jumlah_guru_paud' => $queryPendidikan->sum('jumlah_guru_paud'),
                    'jumlah_siswa_paud' => $queryPendidikan->sum('jumlah_siswa_paud'),
                ];
            }
        } else {
            $dataTabel = [];
            // Quartal
            foreach (semester() as $key => $kuartal) {
                $queryPendidikan = FasilitasPAUD::whereRaw('semester in ('.$this->getIdsSemester($key).')')
                    ->where('tahun', $year);
                if ($did != 'Semua') {
                    $queryPendidikan->where('desa_id', '=', $did);
                }
                $dataTabel[] = [
                    'year' => 'Semester '.$key,
                    'jumlah_paud' => $queryPendidikan->sum('jumlah_paud'),
                    'jumlah_guru_paud' => $queryPendidikan->sum('jumlah_guru_paud'),
                    'jumlah_siswa_paud' => $queryPendidikan->sum('jumlah_siswa_paud'),
                ];
            }

            $dataPendidikan = $dataTabel;
        }

        // Data Tabel AKI & AKB
        $tabelKesehatan = [];

        return [
            'grafik' => $dataPendidikan,
            'tabel' => $tabelKesehatan,
        ];
    }

    private function getIdsSemester($smt)
    {
        $semester = semester()[$smt];
        $ids = '';
        foreach ($semester as $key => $val) {
            $ids .= $key.',';
        }

        return rtrim($ids, ',');
    }
}