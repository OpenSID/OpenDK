<?php

namespace App\Http\Requests;

use App\Models\LembagaAnggota;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLembagaAnggotaRequest extends FormRequest
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
        $rules = [
            // 'no_anggota' => 'required|unique:das_lembaga_anggota,no_anggota', // pastikan id penduduk ada dalam tabel
            'jabatan_id' => 'required|in:1,2,3,4,5', // kode jabatan harus 1-5
        ];

        $id = $this->route('id');

        // cek no_anggota berdasarkan id
        $anggota = LembagaAnggota::findOrFail($id);
        
        // jika inputan nomor anggota tidak sama dengan data no_anggota
        if($this->input('no_anggota') !== $anggota->no_anggota){
            $rules['no_anggota'] = 'required|unique:das_lembaga_anggota,no_anggota';
        }

        return $rules;

    }

    /**
     * Customize the attributes name for validation messages.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'no_anggota' => 'Nomor Anggota',
            'jabatan_id' => 'Jabatan',
        ];
    }
}
