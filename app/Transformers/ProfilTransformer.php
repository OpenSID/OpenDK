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

use App\Models\Profil;
use App\Services\DesaService;
use App\Traits\BaganTrait;
use League\Fractal\TransformerAbstract;

class ProfilTransformer extends TransformerAbstract
{
    use BaganTrait;
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'dataUmum',
        'dataDesa',
        'strukturOrganisasi'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Profil $profil
     * @return array
     */
    public function transform(Profil $profil): array
    {
        $profil->file_struktur_organisasi_path = is_img($profil->file_struktur_organisasi);
        $profil->foto_kepala_wilayah_path = is_img($profil->foto_kepala_wilayah, 'img/no-profile.png');        
        $profil->file_logo_path = is_img($profil->file_logo);
        return $profil->toArray();
    }

    /**
     * Include Data Umum
     *
     * @param Profil $profil
     * @return \League\Fractal\Resource\Item|null
     */
    public function includeDataUmum(Profil $profil)
    {
        $dataUmum = $profil->dataUmum;
        
        if ($dataUmum) {
            return $this->item($dataUmum, new DataUmumTransformer(), 'dataUmum');
        }

        return null;
    }

    /**
     * Include Data Desa
     *
     * @param Profil $profil
     * @return \League\Fractal\Resource\Collection|null
     */
    public function includeDataDesa(Profil $profil)
    {
        $dataDesa = (new DesaService())->listDesa();
        
        if ($dataDesa) {
            return $this->collection($dataDesa, new DataDesaTransformer(), 'dataDesa');
        }

        return null;
    }

    public function includeStrukturOrganisasi(Profil $profil)
    {        
        return $this->collection([['id' => 1, $this->getDataStrukturOrganisasi()]], function($item){
            return $item;
        }, 'strukturOrganisasi');
    }
}