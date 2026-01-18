<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachSkillsRequest extends FormRequest
{
    public function authorize(): bool
    {
        // auth is ensured by route middleware; additional policy check happens in controller
        return $this->user() != null;
    }

    public function rules(): array
    {
        return [
            'skills' => ['required', 'array', 'min:1'],
            'skills.*' => ['integer', 'exists:skills,id'],
        ];
    }
}
