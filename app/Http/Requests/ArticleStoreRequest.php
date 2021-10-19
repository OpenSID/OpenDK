<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
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
            'title'             => 'required',
            'image'             => 'required',
            'description'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required'             => 'Nama artikel tidak boleh kosong',
            'title.unique'               => 'Nama artikel tersebut sudah ada',
            'image.required'             => 'Gambar tidak boleh kosong',
            'description.required'       => 'Deskripsi tidak boleh kosong',
        ];
    }
}
