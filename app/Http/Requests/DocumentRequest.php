<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'document_id' => 'nullable|integer',
            'jenis_surat' => 'required|string|max:255',
            'judul_document' => 'required|string|max:255',
            'keterangan' => 'required|string|max:100000',
        ];
    }
}
