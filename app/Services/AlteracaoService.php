<?php

namespace App\Services;

use App\Models\Alteracao;
use App\Models\Aplicacao;
use Illuminate\Pagination\LengthAwarePaginator;

class AlteracaoService
{
    // ─── Geração automática ─────────────────────────────────────────────────

    /**
     * Registra um evento de histórico vinculado a uma aplicação — RF-04.8.
     */
    public function registrar(Aplicacao $aplicacao, string $descricao): Alteracao
    {
        return Alteracao::create([
            'user_id'      => auth()->id(),
            'aplicacao_id' => $aplicacao->id,
            'descricao'    => $descricao,
        ]);
    }

    // ─── Leitura ────────────────────────────────────────────────────────────

    /**
     * Lista com busca por descrição, usuário, aplicação e data (dd/mm ou dd/mm/yyyy) — RF-05.1 / RF-05.4.
     */
    public function listar(array $filtros = []): LengthAwarePaginator
    {
        $query = Alteracao::with(['usuario', 'aplicacao'])
            ->orderByDesc('alteracoes.created_at');

        if (!empty($filtros['busca'])) {
            $busca = $filtros['busca'];
            $query->where(function ($q) use ($busca) {
                $q->where('alteracoes.descricao', 'like', "%{$busca}%")
                  ->orWhereHas('usuario', fn ($sq) =>
                      $sq->where('name', 'like', "%{$busca}%")
                  )
                  ->orWhereHas('aplicacao', fn ($sq) =>
                      $sq->where('nome_aplicacao', 'like', "%{$busca}%")
                  )
                  // Cobre dd/mm e dd/mm/yyyy com um único LIKE sobre a data formatada
                  ->orWhereRaw(
                      "DATE_FORMAT(alteracoes.created_at, '%d/%m/%Y') LIKE ?",
                      ["%{$busca}%"]
                  );
            });
        }

        return $query->paginate(20)->withQueryString();
    }

    // ─── Escrita ────────────────────────────────────────────────────────────

    /**
     * Atualiza a descrição — restrito ao autor ou Administrador (RF-05.5).
     */
    public function atualizar(Alteracao $alteracao, array $dados): void
    {
        $alteracao->update(['descricao' => $dados['descricao']]);
    }

    /**
     * Remove o registro — restrito a Administradores (RF-05.6).
     */
    public function excluir(Alteracao $alteracao): void
    {
        $alteracao->delete();
    }
}
