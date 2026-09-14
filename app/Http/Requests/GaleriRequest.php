<?php

namespace App\Http\Requests;

use App\Services\FileUploadService;
use Illuminate\Foundation\Http\FormRequest;

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
            'link' => $isUrl ? 'required|string|max:255' : 'nullable',
            'gambar' => (! $isUrl && $this->isMethod('post')) ? 'required|array' : 'nullable|array',
            'gambar.*' => $isUrl ? 'nullable' : 'required|image|mimes:jpg,jpeg,png' . $maxRule . '|valid_file',
            'status' => 'required',
        ];
    }
}
