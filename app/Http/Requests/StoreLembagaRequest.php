<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLembagaRequest extends FormRequest
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
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:das_lembaga,kode',
            'lembaga_kategori_id' => 'required|exists:das_lembaga_kategori,id',
            'penduduk_id' => 'required|exists:das_penduduk,id',
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
            'nama' => 'Nama Lembaga',
            'kode' => 'Kode Lembaga',
            'lembaga_kategori_id' => 'Kategori Lembaga',
            'penduduk_id' => 'Ketua Lembaga',
        ];
    }
}
