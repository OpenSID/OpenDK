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

use App\Models\DataUmum;
use League\Fractal\TransformerAbstract;

class DataUmumTransformer extends TransformerAbstract
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
     * @param DataUmum $dataUmum
     * @return array
     */
    public function transform(DataUmum $dataUmum): array
    {
        return [
            'id' => (int) $dataUmum->id,
            'profil_id' => (int) $dataUmum->profil_id,
            'tipologi' => $dataUmum->tipologi,
            'sejarah' => $dataUmum->sejarah,
            'ketinggian' => $dataUmum->ketinggian,
            'sumber_luas_wilayah' => (int) $dataUmum->sumber_luas_wilayah,
            'luas_wilayah' => (float) $dataUmum->luas_wilayah,
            'luas_wilayah_value' => (float) $dataUmum->luas_wilayah_value,
            'luas_wilayah_dari_data_desa' => (float) $dataUmum->luas_wilayah_dari_data_desa,
            'batas_wilayah' => [
                'utara' => $dataUmum->bts_wil_utara,
                'timur' => $dataUmum->bts_wil_timur,
                'selatan' => $dataUmum->bts_wil_selatan,
                'barat' => $dataUmum->bts_wil_barat,
            ],
            'fasilitas_kesehatan' => [
                'puskesmas' => (int) $dataUmum->jml_puskesmas,
                'puskesmas_pembantu' => (int) $dataUmum->jml_puskesmas_pembantu,
                'posyandu' => (int) $dataUmum->jml_posyandu,
                'pondok_bersalin' => (int) $dataUmum->jml_pondok_bersalin,
            ],
            'fasilitas_pendidikan' => [
                'paud' => (int) $dataUmum->jml_paud,
                'sd' => (int) $dataUmum->jml_sd,
                'smp' => (int) $dataUmum->jml_smp,
                'sma' => (int) $dataUmum->jml_sma,
            ],
            'fasilitas_umum' => [
                'masjid_besar' => (int) $dataUmum->jml_masjid_besar,
                'mushola' => (int) $dataUmum->jml_mushola,
                'gereja' => (int) $dataUmum->jml_gereja,
                'pasar' => (int) $dataUmum->jml_pasar,
                'balai_pertemuan' => (int) $dataUmum->jml_balai_pertemuan,
            ],
            'peta' => [
                'embed_peta' => $dataUmum->embed_peta,
                'path' => $dataUmum->path,
                'lat' => (float) $dataUmum->lat,
                'lng' => (float) $dataUmum->lng,
            ],
            'created_at' => $dataUmum->created_at,
            'updated_at' => $dataUmum->updated_at,
        ];
    }

    /**
     * Include Profil
     *
     * @param DataUmum $dataUmum
     * @return \League\Fractal\Resource\Item|null
     */
    public function includeProfil(DataUmum $dataUmum)
    {
        $profil = $dataUmum->profil;
        
        if ($profil) {
            return $this->item($profil, new ProfilTransformer(), 'profil');
        }

        return null;
    }
}