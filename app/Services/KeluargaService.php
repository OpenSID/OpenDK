<?php

namespace App\Services;

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Http;

class KeluargaService extends BaseApiService
{
    public function keluarga(int $id)
    {
        // Default parameter
        $defaultParams = [
            'filter[id]' => $id,
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams);

        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/keluarga', $params);

        $result = collect($data)
            ->map(function ($item) {
                return (object) [
                    'id' => $item['id'],
                    'no_kk' => $item['attributes']['no_kk'] ?? null,
                    'nik_kepala' => $item['attributes']['nik_kepala'] ?? null,
                    'nama_kk' => $item['attributes']['nama_kk'] ?? null,
                    'tgl_daftar' => $item['attributes']['tgl_daftar'] ?? null,
                    'tgl_cetak_kk' => $item['attributes']['tgl_cetak_kk'] ?? null,
                    'desa' => $item['attributes']['desa'] ?? null,
                    'alamat' => $item['attributes']['alamat_plus_dusun'] ?? null,
                    'rt' => $item['attributes']['rt'] ?? null,
                    'rw' => $item['attributes']['rw'] ?? null,
                    'anggota' => $item['attributes']['anggota'] ?? null
                ];
            });
        
        return $result[0];
    }

    /**
     * Get Unique Desa
     */
    public function jumlahKeluarga(array $filters = [])
    {
        // Default parameter
        $defaultParams = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams, $filters);

        // Panggil API dan ambil data
        $data = $this->apiRequestLengkap('/api/v1/keluarga', $params);
        if (!isset($data['meta']['pagination']['total'])) {
            return 0; // Jika tidak ada total, kembalikan 0
        }
        // Jika total tersedia, kembalikan nilainya
        return $data['meta']['pagination']['total'];
    }

    /**
     * Export Data Keluarga
     */
    public function exportKeluarga(array $params = [], $all = false)
    {
        // Default parameter
        $defaultParams = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
            'filter[kode_desa]' => request()->desa,
            'all' => $all
        ];

        // Gabungkan parameter default dengan filter dinamis
        $finalParams = array_merge($defaultParams, $params);

        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/keluarga', $finalParams);
        
        // Format ulang data jika diperlukan
        return collect($data)->map(function ($item) {
            return (object) [
                'id' => $item['id'],
                'nik_kepala' => $item['attributes']['nik_kepala'] ?? '',
                'kepala_kk' => (object) ['nama' => $item['attributes']['nama_kk'] ?? ''],
                'no_kk' => $item['attributes']['no_kk'] ?? '',
                'alamat' => $item['attributes']['alamat'] ?? '',
                'dusun' => $item['attributes']['dusun'] ?? '',
                'rw' => $item['attributes']['rw'] ?? '',
                'rt' => $item['attributes']['rt'] ?? '',
                'desa' => (object) ['nama' => $item['attributes']['desa'] ?? ''],
                'tgl_daftar' => $item['attributes']['tgl_daftar'] ?? null,
                'tgl_cetak_kk' => (isset($item['attributes']['tgl_cetak_kk']) && $item['attributes']['tgl_cetak_kk'] !== '-') ? $item['attributes']['tgl_cetak_kk'] : null,
                'created_at' => null,
                'updated_at' => null,
            ];
        });
    }

}
