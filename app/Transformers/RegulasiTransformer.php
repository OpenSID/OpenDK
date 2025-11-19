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
        $data = $regulasi->toArray();
        $data['file_regulasi_path'] = $data['file_regulasi'] ? asset('storage/' . $data['file_regulasi']) : null;
        return $data;
    }
}