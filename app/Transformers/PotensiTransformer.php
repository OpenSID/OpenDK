<?php

namespace App\Transformers;

use App\Models\Potensi;
use League\Fractal\TransformerAbstract;

class PotensiTransformer extends TransformerAbstract
{

    /**
     * Transform object data
     *
     * @param Potensi $potensi
     * @return array
     */
    public function transform(Potensi $potensi): array
    {
        $data = $potensi->toArray();
        $data['file_gambar_path'] = $data['file_gambar'] ? asset('storage/' . $data['file_gambar']) : null;
        return $data;
    }
}