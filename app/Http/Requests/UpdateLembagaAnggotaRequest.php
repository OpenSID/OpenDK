<?php

namespace App\Http\Requests;

use App\Models\LembagaAnggota;
use App\Models\SettingAplikasi;
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
            'jabatan_id' => 'required|in:1,2,3,4,5',
        ];

        $id = $this->route('id');

        $anggota = LembagaAnggota::findOrFail($id);

        if ($this->input('no_anggota') !== $anggota->no_anggota) {
            $rules['no_anggota'] = 'required|unique:das_lembaga_anggota,no_anggota';
        }

        if (SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->value('value') === '1') {
            $rules['penduduk_id'] = 'nullable|integer';
            $rules['penduduk_id_gabungan'] = 'required|integer';
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
            'penduduk_id' => 'Nama Anggota',
            'penduduk_id_gabungan' => 'Nama Anggota',
            'no_anggota' => 'Nomor Anggota',
            'jabatan_id' => 'Jabatan',
        ];
    }
}
