<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesaRequest extends FormRequest
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
        if ($this->id) {
            $id = "," . $this->id;
        } else {
            $id = "";
        }

        return [
            'desa_id'      => 'required|regex:/^[0-9.]+$/|min:13|max:13|unique:das_data_desa,desa_id' . $id,
            'nama'         => 'required|string',
            'website'      => 'nullable|url',
            'luas_wilayah' => 'required|numeric',
        ];
    }
}
