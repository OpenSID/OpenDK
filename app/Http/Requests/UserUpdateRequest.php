<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
           $id = ",".$this->segment(4);
        } else {
           $id = "";
        }
        return [
            'first_name' => 'required|regex:/^[a-zA-Z]+$/u|max:255',
            'last_name'  => 'required|regex:/^[A-Za-z]+(\s[A-Za-z]+)?$/u|max:255',
            'email'      => 'required|email|unique:users,email'.$id,
            'phone'      => 'numeric|digits_between:10,13',
            'address'    => 'required'
        ];
    }
}
