<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if ($this->isMethod('put')) {
            $id = "," . $this->segment(4);
        } else {
            $id = "";
        }
        return [
            'name'       => 'required|regex:/^[A-Za-z\.\']+(?:\s[A-Za-z\.\']+)*$/u|max:255',
            'email'      => 'required|email|unique:users,email' . $id,
            'phone'      => 'numeric|digits_between:10,13',
            'password'   => 'required|min:8|max:32',
            'address'    => 'required',
        ];
    }
}
