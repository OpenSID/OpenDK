<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KirimBalasanRequest extends FormRequest
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
            'captcha_main' => 'required|captcha',
            'das_artikel_id' => 'required|exists:das_artikel,id',
            'comment_id' => 'required|exists:das_artikel_comment,id',
        ];
    }
}
