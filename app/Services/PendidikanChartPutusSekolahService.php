<?php

namespace App\Services;

use App\Models\DataDesa;
use App\Models\PutusSekolah;
use Illuminate\Support\Facades\DB;

class PendidikanChartPutusSekolahService
{
    public $nama_kuartal = ['q1' => 'Kuartal 1', 'q2' => 'Kuartal 2', 'q3' => 'Kuartal 3', 'q4' => 'Kuartal 4'];

    public function chart($did, $year)
    {
        // Grafik Data Siswa PAUD
        $dataPendidikan = [];
        if ($year == 'Semua' && $did == 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $queryPendidikan = PutusSekolah::query()
                    ->where('tahun', '=', $yearl);

                $dataPendidikan[] = [
                    'year' => $yearl,
                    'siswa_paud' => $queryPendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $queryPendidikan->sum('anak_usia_paud'),
                    'siswa_sd' => $queryPendidikan->sum('siswa_sd'),
                    'anak_usia_sd' => $queryPendidikan->sum('anak_usia_sd'),
                    'siswa_smp' => $queryPendidikan->sum('siswa_smp'),
                    'anak_usia_smp' => $queryPendidikan->sum('anak_usia_smp'),
                    'siswa_sma' => $queryPendidikan->sum('siswa_sma'),
                    'anak_usia_sma' => $queryPendidikan->sum('anak_usia_sma'),
                ];
            }
        } elseif ($year == 'Semua' && $did != 'Semua') {
            foreach (years_list() as $yearl) {
                // SD
                $queryPendidikan = PutusSekolah::query()
                    ->where('tahun', '=', $yearl)
                    ->where('desa_id', $did);

                $dataPendidikan[] = [
                    'year' => $yearl,
                    'siswa_paud' => $queryPendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $queryPendidikan->sum('anak_usia_paud'),
                    'siswa_sd' => $queryPendidikan->sum('siswa_sd'),
                    'anak_usia_sd' => $queryPendidikan->sum('anak_usia_sd'),
                    'siswa_smp' => $queryPendidikan->sum('siswa_smp'),
                    'anak_usia_smp' => $queryPendidikan->sum('anak_usia_smp'),
                    'siswa_sma' => $queryPendidikan->sum('siswa_sma'),
                    'anak_usia_sma' => $queryPendidikan->sum('anak_usia_sma'),
                ];
            }
        } elseif ($year != 'Semua' && $did == 'Semua') {
            $desa = DataDesa::all();
            foreach ($desa as $value) {
                // SD
                $queryPendidikan = PutusSekolah::query()
                    ->where('tahun', '=', $year)
                    ->where('desa_id', $value->desa_id);

                $dataPendidikan[] = [
                    'year' => $value->nama,
                    'siswa_paud' => $queryPendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $queryPendidikan->sum('anak_usia_paud'),
                    'siswa_sd' => $queryPendidikan->sum('siswa_sd'),
                    'anak_usia_sd' => $queryPendidikan->sum('anak_usia_sd'),
                    'siswa_smp' => $queryPendidikan->sum('siswa_smp'),
                    'anak_usia_smp' => $queryPendidikan->sum('anak_usia_smp'),
                    'siswa_sma' => $queryPendidikan->sum('siswa_sma'),
                    'anak_usia_sma' => $queryPendidikan->sum('anak_usia_sma'),
                ];
            }
        } elseif ($year != 'Semua' && $did != 'Semua') {
            $dataTabel = [];
            // Quartal
            foreach (semester() as $key => $kuartal) {
                $queryPendidikan = PutusSekolah::query()
                    // ->whereRaw('bulan in ('.$this->getIdsSemester($key).')')
                    ->where('tahun', $year)
                    ->where('desa_id', '=', $did);

                $dataTabel[] = [
                    'year' => 'Semester '.$key,
                    'siswa_paud' => $queryPendidikan->sum('siswa_paud'),
                    'anak_usia_paud' => $queryPendidikan->sum('anak_usia_paud'),
                    'siswa_sd' => $queryPendidikan->sum('siswa_sd'),
                    'anak_usia_sd' => $queryPendidikan->sum('anak_usia_sd'),
                    'siswa_smp' => $queryPendidikan->sum('siswa_smp'),
                    'anak_usia_smp' => $queryPendidikan->sum('anak_usia_smp'),
                    'siswa_sma' => $queryPendidikan->sum('siswa_sma'),
                    'anak_usia_sma' => $queryPendidikan->sum('anak_usia_sma'),
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