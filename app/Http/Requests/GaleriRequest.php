<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GaleriRequest extends FormRequest
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
            'judul' => 'required|string|max:191',
            'jenis' => 'required',
            'link' => $this->input('jenis') == 'url' ? 'required|max:255' : 'nullable',
            'gambar.*' => $this->input('jenis') == 'url' ? 'nullable' : 'required|image|mimes:jpg,jpeg,png|max:1024',
            'status' => 'required',
        ];
    }
}
