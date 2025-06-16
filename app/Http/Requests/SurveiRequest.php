<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveiRequest extends FormRequest
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
        return [
            'optionsRadios' => 'required|in:option1,option2,option3,option4',
            'consent' => 'required|accepted',
        ];
    }
}
