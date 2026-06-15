<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->usuario)],
            'role'  => 'required|in:administrador,operador',

            // Senha é opcional — só valida se preenchida
            'nova_senha'              => ['nullable', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
            'nova_senha_confirmation' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nova_senha.confirmed' => 'A confirmação de senha não confere.',
            'nova_senha.min'       => 'A senha deve ter no mínimo 8 caracteres.',
        ];
    }
}
