<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:200', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['client', 'freelancer'])],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => trim($this->input('name')),
            'email' => strtolower(trim($this->input('email'))),
        ]);
    }
}
