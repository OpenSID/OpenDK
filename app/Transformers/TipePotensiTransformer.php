<?php

namespace App\Transformers;

use App\Models\TipePotensi;
use League\Fractal\TransformerAbstract;

class TipePotensiTransformer extends TransformerAbstract
{
    /**
     * Transform object data.
     */
    public function transform(TipePotensi $tipePotensi): array
    {
        return $tipePotensi->toArray();
    }
}
