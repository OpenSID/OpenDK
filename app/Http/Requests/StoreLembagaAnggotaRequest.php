<?php

namespace App\Http\Requests;

use App\Models\Lembaga;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLembagaAnggotaRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $slug = $this->route('slug'); 
        $lembaga = Lembaga::where('slug', $slug)->first();
        
        return [
            'penduduk_id' => 'required|exists:das_penduduk,id',
            'no_anggota' => [
                'required',
                Rule::unique('das_lembaga_anggota', 'no_anggota')
                    ->where('lembaga_id', $lembaga->id),
            ],
            'jabatan_id' => 'required|in:1,2,3,4,5', // Kode jabatan harus 1-5
        ];
    }

    /**
     * Customize the attributes name for validation messages.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'penduduk_id' => 'Nama Anggota',
            'no_anggota' => 'Nomor Anggota',
            'jabatan_id' => 'Jabatan',
        ];
    }
}
