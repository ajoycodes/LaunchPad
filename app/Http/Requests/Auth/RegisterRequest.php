<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Anyone (a guest) may register.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for registration.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-z0-9_]+$/', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:maker,hunter'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Usernames are stored lowercase with no surrounding whitespace.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('username')) {
            $this->merge(['username' => strtolower(trim($this->input('username')))]);
        }
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.regex' => 'Usernames may only contain lowercase letters, numbers, and underscores.',
            'username.unique' => 'That username is already taken.',
        ];
    }
}
