<?php

namespace App\Http\Requests;

use App\Models\SettingAplikasi;
use Illuminate\Foundation\Http\FormRequest;

class StoreLembagaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:das_lembaga,kode',
            'lembaga_kategori_id' => 'required|exists:das_lembaga_kategori,id',
        ];

        if (SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->value('value') === '1') {
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
