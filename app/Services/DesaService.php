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
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use SimplePie;

class DesaService extends BaseApiService
{
    public function dataDesa(string $slug)
    {
        if ($this->useDatabaseGabungan()) {
            $slug = kembalikanSlug($slug);
            $dataDesa = $this->desa(['filter[nama_desa]' => $slug, 'page[size]' => 1])?->first();

            return $dataDesa;
        }

        return DataDesa::whereNama($slug)->firstOrFail();
    }

    public function listDesa($all = false)
    {
        if ($this->useDatabaseGabungan()) {
            // gunakan cache untuk mempercepat load data melalui api
            $dataDesa = Cache::get('listDesa');
            // jika kosong, maka request ulang ke API
            if (! $dataDesa || $dataDesa->isEmpty()) {
                $dataDesa = $this->desa(['filter[kode_kec]' => $this->kodeKecamatan, 'all' => $all]);
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
                'desa_id' => $item['attributes']['kode_desa'] ?? null,
                'kode_desa' => $item['attributes']['kode_desa'] ?? null,
                'nama' => $item['attributes']['nama_desa'] ?? null,
                'sebutan_desa' => $item['attributes']['sebutan_desa'] ?? null,
                'website' => $item['attributes']['website'] ?? null,
                'luas_wilayah' => $item['attributes']['luas_wilayah'] ?? null,
                'path' => $item['attributes']['path'] ?? null,
            ];
        });
    }

    /**
     * Get feeds for all desa
     */
    public function getFeeds(): array
    {
        // Cache feeds for 1 hour to improve performance
        return Cache::remember('desa_feeds', 60 * 60, function () {
            try {                
                $allDesa = $this->listDesa()->map(function($item){
                        return ($item instanceof DataDesa) ? $item : new DataDesa((array) $item);
                    })->filter(static fn($q) => !empty($q->website));
                $feeds = [];
                foreach ($allDesa as $desa) {
                    $feedReader = new SimplePie();
                    $feedReader->set_feed_url($desa->website_url_feed['website']);
                    $feedReader->set_item_limit(5);
                    $feedReader->force_fsockopen(true);
                    $feedReader->set_cache_location(storage_path('framework/cache/simplepie'));
                    $feedReader->init();
                    $feedReader->handle_content_type();
                    $items = $feedReader->get_items();

                    // Handle case where no items returned
                    if (empty($items)) {
                        Log::warning('No items found for feed', [
                            'desa_id' => $desa->desa_id,
                            'website' => $desa->website
                        ]);
                        continue;
                    }

                    foreach ($items as $item) {
                        try {
                            $feeds[] = [
                                'desa_id' => $desa->desa_id,
                                'nama_desa' => $desa->nama,
                                'feed_link' => $item->get_feed()->get_permalink(),
                                'feed_title' => $item->get_feed()->get_title(),
                                'link' => $item->get_link(),
                                'date' => \Carbon\Carbon::createFromTimestamp((int) $item->get_date('U')),
                                'author' => $item->get_author()->get_name() ?? 'Administrator',
                                'title' => $item->get_title(),
                                'image' => get_tag_image($item->get_description()),
                                'description' => strip_tags(substr(str_replace(['&amp;', 'nbsp;', '[...]'], '', $item->get_description()), 0, 250) . '[...]'),
                                'content' => $item->get_content(),
                            ];
                        } catch (\Throwable $itemError) {
                            Log::error('Error processing feed item', [
                                'desa_id' => $desa->desa_id,
                                'error' => $itemError->getMessage()
                            ]);
                            continue;
                        }
                    }
                }
                
                Log::info('Total feeds collected', ['count' => count($feeds)]);
                return $feeds;
            } catch (Exception $e) {
                Log::error('Error fetching desa feeds', ['message' => $e->getMessage()]);
                return [];
            }
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
        // Jika tidak menggunakan database gabungan, hitung dari database lokal
        if (! $this->useDatabaseGabungan) {
            return DataDesa::count();
        }

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

    /**
     * Dapatkan object desa berdasarkan kode_desa tertentu
     *
     * @param string $kodeDesa
     * @return collection|DataDesa|stdClass|null
     */
    public function getDesaByKode(string $kodeDesa): Collection|DataDesa|stdClass|null
    {
        $listDesa = $this->listDesa();
        return $listDesa->where('desa_id', $kodeDesa)->first();
    }
}
