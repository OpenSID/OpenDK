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

namespace App\Repositories;

use App\Models\Profil;
use Spatie\QueryBuilder\AllowedFilter;

class ProfilApiRepository extends BaseApiRepository
{
    /**
     * Constructor
     */
    public function __construct(Profil $model)
    {
        parent::__construct($model);
        
        // Initialize allowed filters, sorts, and includes
        $this->allowedFilters = [
            'nama_kecamatan', 'nama_kabupaten', 'nama_provinsi',
            AllowedFilter::exact('id'),
            AllowedFilter::exact('kecamatan_id'),
            AllowedFilter::exact('kabupaten_id'),
            AllowedFilter::exact('provinsi_id'),
            AllowedFilter::callback('search', function($query, $value){                
                $query->where('nama_kecamatan', 'LIKE', '%'.$value.'%')
                      ->orWhere('nama_kabupaten', 'LIKE', '%'.$value.'%')
                      ->orWhere('nama_provinsi', 'LIKE', '%'.$value.'%')
                      ->orWhere('alamat', 'LIKE', '%'.$value.'%');
            }),
        ];
        $this->allowedSorts = ['nama_kecamatan', 'nama_kabupaten', 'nama_provinsi', 'created_at', 'updated_at', 'id'];
        $this->allowedIncludes = ['dataUmum', 'dataDesa','strukturOrganisasi'];
        $this->defaultSort = 'nama_kecamatan';
    }
    
    public function data(){        
        return $this->getFilteredApi()->jsonPaginate();
    }    
}