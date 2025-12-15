<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class DuplikasiDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && app('current_tenant');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_kecamatan' => 'required|string|max:255',
            'id_start_range' => 'required|integer|min:1',
            'id_end_range' => 'required|integer|min:1|gte:id_start_range',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (!$validator->errors()->count()) {
                $tenantCode = $this->input('kode_kecamatan');
                $idStartRange = $this->input('id_start_range');
                $idEndRange = $this->input('id_end_range');

                // Check if the requested range [idStartRange, idEndRange] overlaps with 
                // any existing tenant's range in the tenants table (excluding the target tenant if it exists)
                $existingTenants = \DB::table('tenants')->where('kode_kecamatan', '!=', $tenantCode)->get();

                foreach ($existingTenants as $existingTenant) {
                    $existingStart = $existingTenant->id_start_range;
                    $existingEnd = $existingTenant->id_end_range;

                    // Two ranges [a,b] and [c,d] overlap if: a <= d AND c <= b
                    if ($idStartRange <= $existingEnd && $existingStart <= $idEndRange) {
                        $validator->errors()->add(
                            'id_range',
                            'Rentang ID ' . $idStartRange . '-' . $idEndRange .
                            ' beririsan dengan rentang ID kecamatan lain (' .
                            $existingTenant->kode_kecamatan . ': ' . $existingStart . '-' . $existingEnd . ')'
                        );
                        break; // Stop at first conflict found
                    }
                }
            }
        });
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'kode_kecamatan.required' => 'Kode kecamatan tujuan wajib diisi.',
            'kode_kecamatan.string' => 'Kode kecamatan tujuan harus berupa teks.',
            'kode_kecamatan.max' => 'Kode kecamatan tujuan tidak boleh lebih dari 255 karakter.',
            'id_start_range.required' => 'ID range awal wajib diisi.',
            'id_start_range.integer' => 'ID range awal harus berupa angka.',
            'id_start_range.min' => 'ID range awal minimal 1.',
            'id_end_range.required' => 'ID range akhir wajib diisi.',
            'id_end_range.integer' => 'ID range akhir harus berupa angka.',
            'id_end_range.min' => 'ID range akhir minimal 1.',
            'id_end_range.gte' => 'ID range akhir harus lebih besar atau sama dengan ID range awal.',
        ];
    }
}
