<?php

namespace App\Http\Requests;

use App\Rules\CekDesa;
use Illuminate\Foundation\Http\FormRequest;

class LaporanPendudukRequest extends FormRequest
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
            "desa_id" => ['required', 'string', new CekDesa()],
            "laporan_penduduk.*.id" => "required|integer",
            "laporan_penduduk.*.judul" => "required|string",
            "laporan_penduduk.*.bulan" => "required|integer",
            "laporan_penduduk.*.tahun" => "required|integer",
            "laporan_penduduk.*.file" => "required|string",
        ];
    }
}
