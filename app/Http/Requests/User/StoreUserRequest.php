<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:users|max:50',
            'email' => 'required|email|unique:users|max:40',
            'password' => 'required|string',
            'lastname' => 'required|string|max:30',
            'firstname' => 'required|string|max:30',
            // 'mobile' => 'required|digits:10',
            // 'indentity' => 'required|digits:12',
        ];
    }
}
