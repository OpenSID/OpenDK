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

use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'artikel',
        'parent_comment',
        'replies'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Comment $comment
     * @return array
     */
    public function transform(Comment $comment): array
    {
        $comment->type = 'comment';
        return $comment->toArray();
    }

    /**
     * Include Artikel
     *
     * @param Comment $comment
     * @return \League\Fractal\Resource\Item|null
     */
    public function includeArtikel(Comment $comment)
    {
        $artikel = $comment->artikel;
        
        if ($artikel) {
            return $this->item($artikel, new ArtikelTransformer());
        }

        return null;
    }

    /**
     * Include Parent Comment
     *
     * @param Comment $comment
     * @return \League\Fractal\Resource\Item|null
     */
    public function includeParentComment(Comment $comment)
    {
        $parentComment = $comment->parentComment;
        
        if ($parentComment) {
            return $this->item($parentComment, new CommentTransformer());
        }

        return null;
    }

    /**
     * Include Replies
     *
     * @param Comment $comment
     * @return \League\Fractal\Resource\Collection|null
     */
    public function includeReplies(Comment $comment)
    {
        $replies = $comment->replies;
        
        if ($replies) {
            return $this->collection($replies, new CommentTransformer());
        }

        return null;
    }
}