<?php

namespace App\Transformers;

use App\Models\Galeri;
use League\Fractal\TransformerAbstract;

class GaleriTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     */
    protected array $availableIncludes = [
        'album'
    ];

    /**
     * Transform object data.
     */
    public function transform(Galeri $galeri): array
    {
        $data = $galeri->toArray();

        return $data;
    }

    /**
     * Include Album.
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeAlbum(Galeri $galeri)
    {
        if ($galeri->album) {
            return $this->item($galeri->album, new \App\Transformers\AlbumTransformer, 'album');
        }

        return null;
    }
}
