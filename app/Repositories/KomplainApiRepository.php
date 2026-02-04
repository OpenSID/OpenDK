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

use App\Models\Komplain;
use Spatie\QueryBuilder\AllowedFilter;

class KomplainApiRepository extends BaseApiRepository
{
    /**
     * Constructor
     */
    public function __construct(Komplain $model)
    {
        parent::__construct($model);
        
        // Initialize allowed filters, sorts, and includes
        $this->allowedFilters = [
            'judul', 'slug', 'laporan','anonim',
            AllowedFilter::exact('id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('kategori'),
            AllowedFilter::exact('nik'),
            AllowedFilter::exact('komplain_id'),
            AllowedFilter::callback('search', function($query, $value){                
                $query->where('judul', 'LIKE', '%'.$value.'%')
                      ->orWhere('laporan', 'LIKE', '%'.$value.'%');
            }),
        ];
        $this->allowedSorts = ['created_at', 'updated_at', 'judul', 'id'];
        $this->allowedIncludes = ['kategori_komplain', 'penduduk', 'jawabs'];
        $this->defaultSort = '-created_at';
    }
    
    public function data(){
        return $this->getFilteredApi()
            ->where('status', '<>', 'DITOLAK')
            ->where('status', '<>', 'REVIEW')
            ->jsonPaginate();
    }        

    /**
     * Create a new complaint
     *
     * @param array $data
     * @return \App\Models\Komplain
     */
    public function create(array $requestData)
    {
        $komplain = new Komplain($requestData);
        
        // Generate ID and slug
        $komplain->komplain_id = Komplain::generateID();
        $komplain->slug = \Illuminate\Support\Str::slug($komplain->judul).'-'.$komplain->komplain_id;
        $komplain->status = 'REVIEW';
        $komplain->dilihat = 0;
        
        return $komplain;
    }

    /**
     * Save complaint with attachments
     *
     * @param \App\Models\Komplain $komplain
     * @param array $attachments
     * @return bool
     */
    public function saveWithAttachments($komplain, array $attachments = [])
    {
        // Handle attachments if provided
        for ($i = 1; $i <= 4; $i++) {
            $attachmentKey = 'lampiran' . $i;
            if (isset($attachments[$attachmentKey]) && $attachments[$attachmentKey] instanceof \Illuminate\Http\UploadedFile) {
                $lampiran = $attachments[$attachmentKey];
                $fileName = $lampiran->getClientOriginalName();
                $path = 'storage/komplain/'.$komplain->komplain_id.'/';
                $lampiran->move($path, $fileName);
                $komplain->{$attachmentKey} = $path.$fileName;
            }
        }
        
        return $komplain->save();
    }

    /**
     * Create a reply for a complaint
     *
     * @param array $data
     * @return \App\Models\JawabKomplain
     */
    public function createReply(array $replyData)
    {
        $jawab = new \App\Models\JawabKomplain();
        $jawab->fill($replyData);
        $jawab->save();
        
        return $jawab;
    }
}