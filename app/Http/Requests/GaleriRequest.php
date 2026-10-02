<?php

namespace App\Http\Requests;

use App\Enums\TipeMedia;
use App\Services\FileUploadService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GaleriRequest extends FormRequest
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
        $isUrl = $this->input('jenis') === 'url';
        $maxRule = FileUploadService::isLimitEnabled() ? '|max:1024' : '';

        return [
            'judul' => 'required|string|max:191',
            'jenis' => 'required|in:file,url',
            'link' => $isUrl ? 'required|string|max:255|url:http,https' : 'nullable',
            'media_type' => [
                'nullable',
                Rule::in(array_keys(TipeMedia::selectable())),
            ],
            'gambar' => (! $isUrl && $this->isMethod('post')) ? 'required|array' : 'nullable|array',
            'gambar.*' => $isUrl ? 'nullable' : 'required|image|mimes:jpg,jpeg,png' . $maxRule . '|valid_file',
            'status' => 'required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'link.url' => 'Link media harus berupa URL yang valid (diawali http:// atau https://).',
            'media_type.in' => 'Tipe media harus salah satu dari: Foto, Video, atau YouTube.',
        ];
    }

    /**
     * Tipe media yang dipilih manual, atau null bila biarkan dideteksi otomatis.
     */
    public function selectedMediaType(): ?string
    {
        $mediaType = $this->input('media_type');

        return is_string($mediaType) && $mediaType !== '' ? $mediaType : null;
    }
}
