<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisDocumentRequest extends FormRequest
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
            'id' => 'nullable|integer',
            'nama' => 'required|string|max:225',
        ];
    }
}
