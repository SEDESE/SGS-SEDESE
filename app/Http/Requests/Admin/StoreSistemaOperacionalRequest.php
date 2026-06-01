<?php

namespace App\Http\Requests\Admin;

use App\Enums\FamiliaOS;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreSistemaOperacionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'    => 'required|string|max:255|unique:sistemas_operacionais,nome',
            'familia' => ['required', new Enum(FamiliaOS::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.unique' => 'Já existe um Sistema Operacional com este nome.',
        ];
    }
}
