<?php

namespace App\Http\Requests;

use App\Enums\Ambiente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateAplicacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'so_id'                 => 'nullable|exists:sistemas_operacionais,id',
            'nome_aplicacao'        => 'required|string|max:255',
            'descricao'             => 'nullable|string|max:2000',
            'ip'                    => 'nullable|string|max:45',
            'ambiente'              => ['nullable', new Enum(Ambiente::class)],
            'url'                   => 'nullable|string|max:255',
            'usuario_os'            => 'nullable|string|max:255',
            'senha_os'              => 'nullable|string',           // vazio = mantém senha atual
            'usuario_site'          => 'nullable|string|max:255',
            'senha_site'            => 'nullable|string',
            'database'              => 'nullable|string|max:255',
            'usuario_db'            => 'nullable|string|max:255',
            'senha_db'              => 'nullable|string',
            'caminho'               => 'nullable|string|max:500',
            'git'                   => 'nullable|string|max:500',
            'empresa_desenvolvedor' => 'nullable|string|max:255',
            'responsavel_diretor'   => 'nullable|string|max:255',
            'tecnologias'           => 'nullable|array',
            'tecnologias.*'         => 'string|max:100',
        ];
    }
}
