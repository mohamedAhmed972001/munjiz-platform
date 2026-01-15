<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required','email'],
            'password' => ['required','string'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'email' => strtolower(trim($this->input('email'))),
        ]);
    }
}
