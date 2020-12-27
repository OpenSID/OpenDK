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
            // Batch insert atau update validation
            "apbdes.*.id_apbdes"             => "required|integer",
            "apbdes.*.nama"                  => "required",
            "apbdes.*.tahun"                 => "required|int",
            "apbdes.*.semester"              => "required|int",
            "apbdes.*.tgl_upload"            => "required|date",
            "apbdes.*.nama_file"             => "nullable",
            "apbdes.*.mime_type"             => "nullable",
            "apbdes.*.desa_id"               => "required|string|exists:das_data_desa,desa_id",
            "apbdes.*.kecamatan_id"          => "required|string|exists:das_data_desa,kecamatan_id",
            "apbdes.*.kabupaten_id"          => "required|string",
            "apbdes.*.provinsi_id"           => "required|string",
            "apbdes.*.created_at"            => "required|date",
            "apbdes.*.updated_at"            => "required|date",

            // Batch delete validation
            "hapus_apbdes.*.id_apbdes" => "present|integer",
            "hapus_apbdes.*.desa_id" => "present|string|exists:das_data_desa,desa_id",
        ];
    }
}
