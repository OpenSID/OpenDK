<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePutusSekolahRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'siswa_paud' => 'required|integer',
            'anak_usia_paud' => 'required|integer',
            'siswa_sd' => 'required|integer',
            'anak_usia_sd' => 'required|integer',
            'siswa_smp' => 'required|integer',
            'anak_usia_smp' => 'required|integer',
            'siswa_sma' => 'required|integer',
            'anak_usia_sma' => 'required|integer',
            'semester' => 'required|integer',
            'tahun' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'siswa_paud.required' => 'Jumlah siswa PAUD wajib diisi.',
            'anak_usia_paud.required' => 'Jumlah anak usia PAUD wajib diisi.',
            'siswa_sd.required' => 'Jumlah siswa SD wajib diisi.',
            'anak_usia_sd.required' => 'Jumlah anak usia SD wajib diisi.',
            'siswa_smp.required' => 'Jumlah siswa SMP wajib diisi.',
            'anak_usia_smp.required' => 'Jumlah anak usia SMP wajib diisi.',
            'siswa_sma.required' => 'Jumlah siswa SMA wajib diisi.',
            'anak_usia_sma.required' => 'Jumlah anak usia SMA wajib diisi.',
            'semester.required' => 'Semester wajib diisi.',
            'tahun.required' => 'Tahun wajib diisi.',
        ];
    }
}
