<?php

namespace App\Transformers;

use App\Models\Album;
use League\Fractal\TransformerAbstract;

class AlbumTransformer extends TransformerAbstract
{
    /**
     * Transform object data.
     */
    public function transform(Album $album): array
    {
        $album->gambar_path = isThumbnail("publikasi/album/{$album->gambar}");
        return $album->toArray();
    }
}
