<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSetingAplikasiRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'key'           => 'required|unique_key:das_setting',
            'value'         => 'required',
            'type'          => 'required|max:30|in:input,textarea',
            'description'   => 'required|max:200',
            'option'        => 'required|valid_json',
            'kategori'      => 'required|max:200'
        ];
    }

    public function messages() {
        return [
            'key.unique_key'     => 'Kata kunci harus unik',
            'value'              => 'Nilai pengaturan tidak boleh kosong.',
            'type.in'            => 'Tipe form harus salah satu dari input atau textarea.',
            'type.max'           => 'Tipe form tidak boleh kosong dan lebih dari 30 karakter..',
            'description.max'    => 'Deskripsi tidak boleh kosong dan lebih dari 200 karakter.',
            'option.valid_json'  => 'Option harus dalam betuk data json.',
            'kategori.required'  => 'Kategori tidak boleh kosong.'
        ];
    }

}
