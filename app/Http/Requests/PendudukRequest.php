<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendudukRequest extends FormRequest
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
            // Batch insert atau update validation
            "penduduk.*.id"                    => "required|integer",
            "penduduk.*.nama"                  => "required",
            "penduduk.*.nik"                   => "required|string|min:16|max:16",
            "penduduk.*.id_kk"                 => "integer|nullable",
            "penduduk.*.kk_level"              => "required",
            "penduduk.*.id_rtm"                => "nullable",
            "penduduk.*.rtm_level"             => "integer|nullable",
            "penduduk.*.sex"                   => "required",
            "penduduk.*.tempatlahir"           => "required",
            "penduduk.*.tanggallahir"          => "required|date_format:Y-m-d",
            "penduduk.*.agama_id"              => "required",
            "penduduk.*.pendidikan_kk_id"      => "required",
            "penduduk.*.pendidikan_sedang_id"  => "integer|nullable",
            "penduduk.*.pekerjaan_id"          => "integer|nullable",
            "penduduk.*.status_kawin"          => "integer|nullable",
            "penduduk.*.warganegara_id"        => "integer|nullable",
            "penduduk.*.dokumen_pasport"       => "nullable",
            "penduduk.*.dokumen_kitas"         => "nullable",
            "penduduk.*.ayah_nik"              => "integer|nullable",
            "penduduk.*.ibu_nik"               => "integer|nullable",
            "penduduk.*.nama_ayah"             => "required|string",
            "penduduk.*.nama_ibu"              => "required|string",
            "penduduk.*.foto"                  => "nullable",
            "penduduk.*.golongan_darah_id"     => "nullable",
            "penduduk.*.id_cluster"            => "nullable",
            "penduduk.*.status"                => "nullable",
            "penduduk.*.alamat_sebelumnya"     => "nullable",
            "penduduk.*.alamat_sekarang"       => "nullable",
            "penduduk.*.status_dasar"          => "required|integer",
            "penduduk.*.hamil"                 => "nullable",
            "penduduk.*.cacat_id"              => "nullable|integer",
            "penduduk.*.sakit_menahun_id"      => "nullable|integer",
            "penduduk.*.akta_lahir"            => "nullable",
            "penduduk.*.akta_perkawinan"       => "nullable",
            "penduduk.*.tanggalperkawinan"     => "nullable|date_format:Y-m-d",
            "penduduk.*.akta_perceraian"       => "nullable",
            "penduduk.*.tanggalperceraian"     => "nullable|date_format:Y-m-d",
            "penduduk.*.cara_kb_id"            => "nullable|integer",
            "penduduk.*.telepon"               => "nullable",
            "penduduk.*.tanggal_akhir_paspor"  => "nullable|date_format:Y-m-d",
            "penduduk.*.no_kk"                 => "nullable|min:16|max:16",
            "penduduk.*.no_kk_sebelumnya"      => "nullable|min:16|max:16",
            "penduduk.*.ktp_el"                => "nullable",
            "penduduk.*.status_rekam"          => "nullable",
            "penduduk.*.alamat"                => "nullable",
            "penduduk.*.dusun"                 => "nullable",
            "penduduk.*.rw"                    => "nullable",
            "penduduk.*.rt"                    => "nullable",
            "penduduk.*.desa_id"               => "required|string",
            "penduduk.*.tahun"                 => "required|int",
            "penduduk.*.created_at"            => "required|date",
            "penduduk.*.updated_at"            => "required|date",

            // Batch delete validation
            "hapus_penduduk.*.nik" => "present|min:16|max:16",
        ];
    }
}
