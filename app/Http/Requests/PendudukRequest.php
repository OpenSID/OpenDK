<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendudukRequest extends FormRequest
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
            "hapus_penduduk.*.id_pend_desa" => "present|integer",
            "hapus_penduduk.*.foto" => "nullable",
            "hapus_penduduk.*.desa_id" => "present|string|exists:das_data_desa,desa_id",
        ];
    }
}
