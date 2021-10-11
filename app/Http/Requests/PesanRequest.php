<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PesanRequest extends FormRequest
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
        $rules = [];

        if (Request::has('pesan_id')) {
            $rules['pesan']     = 'required';
            $rules['pesan_id']  = 'required|exists:das_pesan,id';
        } else {
            $rules['pesan']     = 'required';
            $rules['judul']     = 'required';
        }

        return $rules;
    }
}
