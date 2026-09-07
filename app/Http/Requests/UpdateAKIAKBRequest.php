<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAKIAKBRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'aki' => 'required|integer',
            'akb' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'aki.required' => 'Jumlah AKI wajib diisi.',
            'akb.required' => 'Jumlah AKB wajib diisi.',
        ];
    }
}
