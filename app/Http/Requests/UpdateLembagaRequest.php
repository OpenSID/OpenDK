<?php

namespace App\Http\Requests;

use App\Models\Lembaga;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLembagaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'lembaga_kategori_id' => 'required|exists:das_lembaga_kategori,id',
        ];

        $id = $this->route('id');
        $lembaga = Lembaga::findOrFail($id);

        if ($this->input('kode') !== $lembaga->kode) {
            $rules['kode'] = 'required|string|max:255|unique:das_lembaga,kode';
        }

        if (SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->value('value') === '1') {
            $rules['penduduk_id'] = 'nullable|integer';
            $rules['penduduk_id_gabungan'] = 'required|integer';
        } else {
            $rules['penduduk_id'] = 'required|exists:das_penduduk,id';
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
            'nama' => 'Nama Lembaga',
            'kode' => 'Kode Lembaga',
            'lembaga_kategori_id' => 'Kategori Lembaga',
            'penduduk_id' => 'Ketua Lembaga',
            'penduduk_id_gabungan' => 'Ketua Lembaga',
        ];
    }
}
