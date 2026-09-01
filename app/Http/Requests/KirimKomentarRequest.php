<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KirimKomentarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'body' => 'required|string',
            'das_artikel_id' => 'required|exists:das_artikel,id',
            'captcha_main' => 'required|captcha',
        ];
    }
}
