<?php

namespace App\Transformers;

use App\Models\FormDokumen;
use League\Fractal\TransformerAbstract;

class FormDokumenTransformer extends TransformerAbstract
{
    /**
     * Transform object data.
     */
    public function transform(FormDokumen $formDokumen): array
    {
        $data = [
            'id' => $formDokumen->id,
            'nama_dokumen' => $formDokumen->nama_dokumen,
            'description' => $formDokumen->description,
            'jenis_dokumen' => [
                'id' => $formDokumen->jenis_dokumen_id,
                'nama' => $formDokumen->jenis_dokumen,
            ],
            'is_published' => $formDokumen->is_published,
            'published_at' => $formDokumen->published_at,
            'retention_days' => $formDokumen->retention_days,
            'expired_at' => $formDokumen->expired_at,
            'file_dokumen_path' => $formDokumen->file_dokumen ? asset($formDokumen->file_dokumen) : null,
            'mime_type' => ($formDokumen->file_dokumen && is_file(public_path($formDokumen->file_dokumen))) ? mime_content_type(public_path($formDokumen->file_dokumen)) : null,
            'created_at' => $formDokumen->created_at,
            'updated_at' => $formDokumen->updated_at,
        ];

        return $data;
    }
}
