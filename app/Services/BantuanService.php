<?php

namespace App\Services;

class BantuanService extends BaseApiService
{
    /**
     * Get Unique Desa
     */
    public function jumlahBantuan(array $filters = [])
    {
        // Default parameter
        $defaultParams = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams, $filters);

        // Panggil API dan ambil data
        $data = $this->apiRequestLengkap('/api/v1/bantuan', $params);
        if (!isset($data['meta']['pagination']['total'])) {
            return 0; // Jika tidak ada total, kembalikan 0
        }
        // Jika total tersedia, kembalikan nilainya
        return $data['meta']['pagination']['total'];
    }
    
}
