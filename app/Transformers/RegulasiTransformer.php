<?php

namespace App\Transformers;

use App\Models\Regulasi;
use League\Fractal\TransformerAbstract;

class RegulasiTransformer extends TransformerAbstract
{
    /**
     * Transform object data.
     */
    public function transform(Regulasi $regulasi): array
    {
        $regulasi->file_regulasi_path = asset($regulasi->file_regulasi);
        $regulasi->path_download = route('unduhan.regulasi.download', ['file' => $regulasi->id]);
        return $regulasi->toArray();
    }
}
