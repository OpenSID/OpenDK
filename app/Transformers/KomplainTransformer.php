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

use App\Models\Komplain;
use League\Fractal\TransformerAbstract;

class KomplainTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'kategori_komplain',
        'penduduk',
        'jawabs'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Komplain $komplain
     * @return array
     */
    public function transform(Komplain $komplain): array
    {
        return [
            'id' => $komplain->id,
            'komplain_id' => $komplain->komplain_id,
            'judul' => $komplain->judul,
            'slug' => $komplain->slug,
            'laporan' => $komplain->laporan,
            'status' => $komplain->status,
            'kategori' => $komplain->kategori,
            'nik' => $komplain->nik,
            'nama' => $komplain->nama,
            'anonim' => $komplain->anonim,
            'dilihat' => $komplain->dilihat,
            'lampiran1' => $komplain->lampiran1,
            'lampiran2' => $komplain->lampiran2,
            'lampiran3' => $komplain->lampiran3,
            'lampiran4' => $komplain->lampiran4,
            'detail_penduduk' => $komplain->detail_penduduk,
            'created_at' => $komplain->created_at,
            'updated_at' => $komplain->updated_at,
        ];
    }

    /**
     * Include Kategori Komplain
     *
     * @param Komplain $komplain
     * @return \League\Fractal\Resource\Item|null
     */
    public function includeKategoriKomplain(Komplain $komplain)
    {
        $kategori = $komplain->kategori_komplain;
        
        if ($kategori) {
            return $this->item($kategori, new \App\Transformers\KategoriKomplainTransformer(), 'kategori_komplain');
        }

        return null;
    }

    /**
     * Include Penduduk
     *
     * @param Komplain $komplain
     * @return \League\Fractal\Resource\Item|null
     */
    public function includePenduduk(Komplain $komplain)
    {
        $penduduk = $komplain->penduduk;
        
        if ($penduduk) {
            return $this->item($penduduk, new \App\Transformers\PendudukTransformer(), 'penduduk');
        }

        return null;
    }

    /**
     * Include Jawaban Komplain
     *
     * @param Komplain $komplain
     * @return \League\Fractal\Resource\Collection|null
     */
    public function includeJawabs(Komplain $komplain)
    {
        $jawabs = $komplain->jawabs;
        
        if ($jawabs) {
            return $this->collection($jawabs, new \App\Transformers\JawabKomplainTransformer(), 'jawabs');
        }

        return null;
    }
}