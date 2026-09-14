<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFasilitasPaudRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jumlah_paud' => 'required|integer',
            'jumlah_guru_paud' => 'required|integer',
            'jumlah_siswa_paud' => 'required|integer',
            'semester' => 'required|integer',
            'tahun' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'jumlah_paud.required' => 'Jumlah PAUD wajib diisi.',
            'jumlah_guru_paud.required' => 'Jumlah guru PAUD wajib diisi.',
            'jumlah_siswa_paud.required' => 'Jumlah siswa PAUD wajib diisi.',
            'semester.required' => 'Semester wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
        ];
    }
}
