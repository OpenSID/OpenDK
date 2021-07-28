<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanApbdesRequest extends FormRequest
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
            "desa_id" => "required|string|exists:das_data_desa,desa_id",
            "laporan_apbdes.*.id" => "required|integer",
            "laporan_apbdes.*.judul" => "required|string",
            "laporan_apbdes.*.tahun" => "required|integer",
            "laporan_apbdes.*.semester" => "required|integer",
            "laporan_apbdes.*.nama_file" => "required|string",
            "laporan_apbdes.*.file" => "required|string",
        ];
    }
}