<?php

namespace App\Transformers;

use App\Models\Regulasi;
use League\Fractal\TransformerAbstract;

class RegulasiTransformer extends TransformerAbstract
{

    /**
     * Transform object data
     *
     * @param Regulasi $regulasi
     * @return array
     */
    public function transform(Regulasi $regulasi): array
    {
        $regulasi->file_regulasi_path = asset($regulasi->file_regulasi);
        $regulasi->path_download = route('unduhan.regulasi.download', ['file' => str_slug($regulasi->judul)]);
        return $regulasi->toArray();        
    }
}