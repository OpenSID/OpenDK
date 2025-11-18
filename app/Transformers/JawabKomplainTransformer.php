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

use App\Models\JawabKomplain;
use League\Fractal\TransformerAbstract;

class JawabKomplainTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'penjawab_komplain'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param JawabKomplain $jawabKomplain
     * @return array
     */
    public function transform(JawabKomplain $jawabKomplain): array
    {
        return [
            'id' => $jawabKomplain->id,
            'komplain_id' => $jawabKomplain->komplain_id,
            'jawaban' => $jawabKomplain->jawaban,
            'penjawab' => $jawabKomplain->penjawab,
            'nik' => $jawabKomplain->nik,
            'created_at' => $jawabKomplain->created_at,
            'updated_at' => $jawabKomplain->updated_at,
        ];
    }

    /**
     * Include Penjawab Komplain
     *
     * @param JawabKomplain $jawabKomplain
     * @return \League\Fractal\Resource\Item|null
     */
    public function includePenjawabKomplain(JawabKomplain $jawabKomplain)
    {
        $penjawab = $jawabKomplain->penjawab_komplain;
        
        if ($penjawab) {
            return $this->item($penjawab, new \App\Transformers\PendudukTransformer(), 'penjawab_komplain');
        }

        return null;
    }
}