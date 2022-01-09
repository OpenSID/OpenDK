<?php

namespace App\Http\Requests;

use App\Models\TingkatPendidikan;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TingkatPendidikanRequest extends FormRequest
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
            'desa_id' => 'required',
            'file'     => 'required|file|mimes:xls,xlsx,csv|max:5120',
            'semester' => 'required',
            'tahun'    => 'required',
        ];
    }
}
