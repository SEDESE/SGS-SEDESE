<?php

namespace App\Http\Requests\Admin;

use App\Enums\FamiliaOS;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateSistemaOperacionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('sistema_operacional')?->id;

        return [
            'nome'    => "required|string|max:255|unique:sistemas_operacionais,nome,{$id}",
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
