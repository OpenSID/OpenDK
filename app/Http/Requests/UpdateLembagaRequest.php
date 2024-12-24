<?php

namespace App\Http\Requests;

use App\Models\Lembaga;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLembagaRequest extends FormRequest
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
        $rules = [
            'nama' => 'required|string|max:255',
            'lembaga_kategori_id' => 'required|exists:das_lembaga_kategori,id',
            'penduduk_id' => 'required|exists:das_penduduk,id',
        ];

        // Dapatkan id lembaga dari route
        $id = $this->route('id');

        // Cek apakah field kode mengalami perubahan
        $lembaga = Lembaga::findOrFail($id);
        
        if ($this->input('kode') !== $lembaga->kode) {
            $rules['kode'] = 'required|string|max:255|unique:das_lembaga,kode';
        }

        return $rules;
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
