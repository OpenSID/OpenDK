<?php

namespace App\Http\Requests\Installer;

use Illuminate\Foundation\Http\FormRequest;

class EnvironmentWizardSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_name'            => 'required|string|max:255',
            'app_environment'     => 'required|string',
            'app_debug'           => 'required',
            'app_url'             => 'required|url',
            'database_connection' => 'required|string',
            'database_hostname'   => 'required|string',
            'database_port'       => 'required|numeric',
            'database_name'       => 'required|string',
            'database_username'   => 'required|string',
            'database_password'   => 'nullable|string',
        ];
    }
}
