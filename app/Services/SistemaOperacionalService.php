<?php

namespace App\Services;

use App\Enums\FamiliaOS;
use App\Models\SistemaOperacional;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SistemaOperacionalService
{
    public function listar(): LengthAwarePaginator
    {
        return SistemaOperacional::orderBy('nome')->paginate(20);
    }

    public function ativos(): Collection
    {
        return SistemaOperacional::ativos()->orderBy('nome')->get();
    }

    public function criar(array $dados): SistemaOperacional
    {
        return SistemaOperacional::create([
            'nome'    => $dados['nome'],
            'familia' => FamiliaOS::from($dados['familia']),
            'ativo'   => true,
        ]);
    }

    public function atualizar(SistemaOperacional $so, array $dados): void
    {
        $so->update([
            'nome'    => $dados['nome'],
            'familia' => FamiliaOS::from($dados['familia']),
        ]);
    }

    public function desativar(SistemaOperacional $so): void
    {
        $so->update(['ativo' => false]);
    }

    public function ativar(SistemaOperacional $so): void
    {
        $so->update(['ativo' => true]);
    }

    /**
     * Exclui o SO apenas se não houver aplicações vinculadas (RF-07.4).
     *
     * @throws \RuntimeException
     */
    public function excluir(SistemaOperacional $so): void
    {
        // Quando o relacionamento com Aplicacao for implementado, trocar pelo check real:
        // if ($so->aplicacoes()->exists()) {
        //     throw new \RuntimeException('Não é possível excluir um SO com aplicações vinculadas.');
        // }

        $so->delete();
    }
}
