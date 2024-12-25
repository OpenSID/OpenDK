<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriLembagaRequest extends FormRequest
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
            'nama' => 'required|string',
        ];
    }

    /**
     * Customize the attributes name for validation messages.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'nama' => 'Kategori Lembaga'
        ];
    }
}
