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
        $data['file_gambar_path'] = is_img($data['file_gambar']);
        $data['slug'] = str_slug($data['nama_potensi']);
        return $data;
    }    
}