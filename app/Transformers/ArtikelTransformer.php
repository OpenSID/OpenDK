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

use App\Models\Artikel;
use League\Fractal\TransformerAbstract;

class ArtikelTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'kategori',
        'comments'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Artikel $artikel
     * @return array
     */
    public function transform(Artikel $artikel): array
    {
        $artikel->tanggal = format_date($artikel->getRawOriginal('created_at'));
        $artikel->link = $artikel->link;
        $artikel->gambar_src = is_img($artikel->gambar);
        return $artikel->toArray();
    }

    /**
     * Include Kategori
     *
     * @param Artikel $artikel
     * @return \League\Fractal\Resource\Item|null
     */
    public function includeKategori(Artikel $artikel)
    {
        $kategori = $artikel->kategori;
        
        if ($kategori) {
            $kategori->link = route('berita-kategori',['slug' => $kategori->slug]);
            return $this->item($kategori, new ArtikelKategoriTransformer(),'kategori');
        }

        return null;
    }

    /**
     * Include Comments
     *
     * @param Artikel $artikel
     * @return \League\Fractal\Resource\Collection|null
     */
    public function includeComments(Artikel $artikel)
    {
        $comments = $artikel->comments;
        
        if ($comments) {
            return $this->collection($comments, new CommentTransformer(),'comments');
        }

        return null;
    }
}