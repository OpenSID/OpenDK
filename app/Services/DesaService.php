<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Services;

use App\Models\DataDesa;
use Illuminate\Support\Facades\Cache;

class DesaService extends BaseApiService
{
    public function dataDesa(string $slug)
    {
        if ($this->useDatabaseGabungan()) {
            $slug = kembalikanSlug($slug);
            $dataDesa = $this->desa(['filter[nama_desa]' => $slug, 'page[size]' => 1])?->first();

            return $dataDesa;
        }

        return DataDesa::nama($slug)->firstOrFail();
    }

    public function listDesa()
    {
        if ($this->useDatabaseGabungan()) {
            // gunakan cache untuk mempercepat load data melalui api
            $dataDesa = Cache::get('listDesa');
            // jika kosong, maka request ulang ke API
            if (! $dataDesa || $dataDesa->isEmpty()) {
                $dataDesa = $this->desa(['filter[kode_kec]' => $this->kodeKecamatan]);
            }
            if ($dataDesa->isNotEmpty()) {
                // simpan ke cache selama 24 jam
                Cache::put('listDesa', $dataDesa, 60 * 60 * 24);
            }

            return $dataDesa;
        }

        return DataDesa::all();
    }

    /**
     * Get Unique Desa
     */
    public function desa(array $filters = [])
    {
        // Panggil API dan ambil data
        $data = $this->apiRequest('/api/v1/wilayah/desa', $filters);
        if (! $data) {
            return collect([]);
        }

        return collect($data)->map(function ($item) {
            return (object) [
                'desa_id' => $item['attributes']['kode_desa'] ?? null, // Ambil kode desa
                'kode_desa' => $item['attributes']['kode_desa'] ?? null, // Ambil kode desa
                'nama' => $item['attributes']['nama_desa'] ?? null, // Ambil nama desa
                'sebutan_desa' => $item['attributes']['sebutan_desa'] ?? null, // Ambil sebutan desa
                'website' => $item['attributes']['website'] ?? null, // Ambil website
                'luas_wilayah' => $item['attributes']['luas_wilayah'] ?? null, // Ambil luas wilayah
                'path' => $item['attributes']['path'] ?? null, // Ambil path
            ];
        });
    }

    public function listPathDesa()
    {
        if ($this->useDatabaseGabungan()) {
            return $this->listDesa();
        }

        return DataDesa::whereNotNull('path')->get();
    }

    /**
     * Get Unique Desa
     */
    public function jumlahDesa(array $filters = [])
    {
        // Default parameter
        $defaultParams = [
            'filter[kode_kecamatan]' => str_replace('.', '', config('profil.kecamatan_id')),
        ];

        // Gabungkan parameter default dengan filter dinamis
        $params = array_merge($defaultParams, $filters);

        // Panggil API dan ambil data
        $data = $this->apiRequestLengkap('/api/v1/desa', $params);
        if (! $data || ! isset($data['meta']['pagination']['total'])) {
            return 0; // Jika tidak ada data atau total tidak tersedia, kembalikan 0
        }
        return $data['meta']['pagination']['total'];
    }
}
