<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Transformers;

use App\Models\DataDesa;
use League\Fractal\TransformerAbstract;

class DataDesaTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'profil'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param DataDesa $dataDesa
     * @return array
     */
    public function transform(DataDesa $dataDesa): array
    {
        return [
            'id' => (int) ($dataDesa->id ?? $dataDesa->desa_id),
            'desa_id' => $dataDesa->desa_id,
            'kode_desa' => $dataDesa->kode_desa,
            'nama' => $dataDesa->nama,
            'sebutan_desa' => $dataDesa->sebutan_desa,
            'nama_lengkap' => ucwords($dataDesa->sebutan_desa . ' ' . $dataDesa->nama),
            'website' => $dataDesa->website,
            'website_url_feed' => $dataDesa->website_url_feed,
            'luas_wilayah' => (float) $dataDesa->luas_wilayah,
            'peta' => [
                'path' => $dataDesa->path,
            ],
            'created_at' => $dataDesa->created_at,
            'updated_at' => $dataDesa->updated_at,
        ];
    }

    /**
     * Include Profil
     *
     * @param DataDesa $dataDesa
     * @return \League\Fractal\Resource\Item|null
     */
    public function includeProfil(DataDesa $dataDesa)
    {
        $profil = $dataDesa->profil;
        
        if ($profil) {
            return $this->item($profil, new ProfilTransformer());
        }

        return null;
    }
}