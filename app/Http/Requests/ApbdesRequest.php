<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApbdesRequest extends FormRequest
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
            // Batch delete validation
            "hapus_apbdes.*.id_apbdes" => "present|integer",
            "hapus_apbdes.*.desa_id" => "present|string|exists:das_data_desa,desa_id",
        ];
    }
}
