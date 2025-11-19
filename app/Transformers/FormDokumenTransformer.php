<?php

namespace App\Transformers;

use App\Models\FormDokumen;
use League\Fractal\TransformerAbstract;

class FormDokumenTransformer extends TransformerAbstract
{

    /**
     * Transform object data
     *
     * @param FormDokumen $formDokumen
     * @return array
     */
    public function transform(FormDokumen $formDokumen): array
    {
        $data = $formDokumen->toArray();
        $data['file_dokumen_path'] = $data['file_dokumen'] ? asset('storage/' . $data['file_dokumen']) : null;
        return $data;
    }
}