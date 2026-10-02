<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateToiletSanitasiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'toilet' => 'required|integer',
            'sanitasi' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'toilet.required' => 'Jumlah toilet wajib diisi.',
            'sanitasi.required' => 'Jumlah sanitasi wajib diisi.',
        ];
    }
}
