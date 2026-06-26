<?php

namespace App\Services;

use App\Enums\Ambiente;
use App\Models\Aplicacao;
use App\Models\Tecnologia;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;

class AplicacaoService
{
    private const CAMPOS_ORDENACAO = [
        'nome_aplicacao', 'ip', 'ambiente', 'url', 'created_at',
    ];

    private const CAMPOS_SENHA = ['senha_os', 'senha_site', 'senha_db'];

    /** Labels legíveis para a descrição de histórico campo a campo. */
    private const LABELS_CAMPOS = [
        'so_id'                 => 'Sistema Operacional',
        'nome_aplicacao'        => 'Nome',
        'descricao'             => 'Descrição',
        'ip'                    => 'IP',
        'ambiente'              => 'Ambiente',
        'url'                   => 'URL',
        'usuario_os'            => 'Usuário SO',
        'senha_os'              => 'Senha SO',
        'usuario_site'          => 'Usuário Aplicação',
        'senha_site'            => 'Senha Aplicação',
        'database'              => 'Banco de Dados',
        'usuario_db'            => 'Usuário Banco',
        'senha_db'              => 'Senha Banco',
        'caminho'               => 'Caminho',
        'git'                   => 'Repositório Git',
        'empresa_desenvolvedor' => 'Empresa/Desenvolvedor',
        'responsavel_diretor'   => 'Responsável/Diretor',
        'tecnologias'           => 'Stack',
    ];

    public function __construct(private AlteracaoService $alteracaoService) {}

    // ─── Leitura ────────────────────────────────────────────────────────────

    public function listar(array $filtros = []): LengthAwarePaginator
    {
        $query = Aplicacao::with(['sistemaOperacional', 'tecnologias']);

        if (!empty($filtros['busca'])) {
            $busca = $filtros['busca'];
            $query->where(function ($q) use ($busca) {
                $q->where('nome_aplicacao',        'like', "%{$busca}%")
                  ->orWhere('ip',                  'like', "%{$busca}%")
                  ->orWhere('url',                 'like', "%{$busca}%")
                  ->orWhere('ambiente',             'like', "%{$busca}%")
                  ->orWhere('usuario_os',           'like', "%{$busca}%")
                  ->orWhere('usuario_site',         'like', "%{$busca}%")
                  ->orWhere('database',             'like', "%{$busca}%")
                  ->orWhere('usuario_db',           'like', "%{$busca}%")
                  ->orWhere('caminho',              'like', "%{$busca}%")
                  ->orWhere('git',                  'like', "%{$busca}%")
                  ->orWhere('empresa_desenvolvedor','like', "%{$busca}%")
                  ->orWhere('responsavel_diretor',  'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%")
                  ->orWhereHas('sistemaOperacional', fn ($sq) =>
                      $sq->where('nome', 'like', "%{$busca}%")
                  );
            });
        }

        $sort      = in_array($filtros['sort'] ?? '', self::CAMPOS_ORDENACAO)
                        ? $filtros['sort']
                        : 'nome_aplicacao';
        $direction = ($filtros['direction'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

        return $query->orderBy($sort, $direction)
                     ->paginate(20)
                     ->withQueryString();
    }

    // ─── Escrita ────────────────────────────────────────────────────────────

    public function criar(array $dados): Aplicacao
    {
        $aplicacao = Aplicacao::create($this->prepararDados($dados));

        $this->sincronizarTecnologias($aplicacao, $dados['tecnologias'] ?? []);

        $this->alteracaoService->registrar(
            $aplicacao,
            "Aplicação \"{$aplicacao->nome_aplicacao}\" criada."
        );

        return $aplicacao;
    }

    public function atualizar(Aplicacao $aplicacao, array $dados): void
    {
        // Senhas fornecidas explicitamente (track antes do fill)
        $senhasAlteradas = array_values(array_filter(
            self::CAMPOS_SENHA,
            fn ($campo) => !empty($dados[$campo])
        ));

        // Detecta mudança na stack antes de sincronizar
        $techAntes  = $aplicacao->tecnologias->pluck('nome')->map(fn ($n) => strtolower($n))->sort()->values()->toArray();
        $techDepois = collect($dados['tecnologias'] ?? [])->map(fn ($n) => strtolower(trim($n)))->filter()->sort()->values()->toArray();
        $stackMudou = $techAntes !== $techDepois;

        $dadosPreparados = $this->prepararDados($dados);
        $aplicacao->fill($dadosPreparados);

        $dirty = array_diff(array_keys($aplicacao->getDirty()), self::CAMPOS_SENHA);
        $todosAlterados = array_unique(array_merge($dirty, $senhasAlteradas));
        if ($stackMudou) {
            $todosAlterados[] = 'tecnologias';
        }

        $aplicacao->save();
        $this->sincronizarTecnologias($aplicacao, $dados['tecnologias'] ?? []);

        $descricao = empty($todosAlterados)
            ? "Aplicação \"{$aplicacao->nome_aplicacao}\" editada sem alterações detectadas."
            : "Campos alterados em \"{$aplicacao->nome_aplicacao}\": " . implode(', ', array_map(
                fn ($c) => self::LABELS_CAMPOS[$c] ?? str_replace('_', ' ', $c),
                $todosAlterados
              )) . '.';

        $this->alteracaoService->registrar($aplicacao, $descricao);
    }

    public function excluir(Aplicacao $aplicacao): void
    {
        // Registra antes de deletar — aplicacao_id vira null via nullOnDelete
        $this->alteracaoService->registrar(
            $aplicacao,
            "Aplicação \"{$aplicacao->nome_aplicacao}\" excluída."
        );

        $aplicacao->delete();
    }

    // ─── Privados ───────────────────────────────────────────────────────────

    private function sincronizarTecnologias(Aplicacao $aplicacao, array $nomes): void
    {
        $ids = collect($nomes)
            ->map(fn ($nome) => trim($nome))
            ->filter()
            ->unique()
            ->map(fn ($nome) => Tecnologia::firstOrCreate(['nome' => $nome])->id)
            ->toArray();

        $aplicacao->tecnologias()->sync($ids);
    }

    private function prepararDados(array $dados): array
    {
        $prepared = [
            'so_id'                 => ($dados['so_id'] ?? null) ?: null,
            'nome_aplicacao'        => $dados['nome_aplicacao'],
            'descricao'             => ($dados['descricao'] ?? null) ?: null,
            'ip'                    => ($dados['ip'] ?? null) ?: null,
            'ambiente'              => !empty($dados['ambiente'] ?? null)
                                          ? Ambiente::from($dados['ambiente'])
                                          : null,
            'url'                   => ($dados['url'] ?? null) ?: null,
            'usuario_os'            => ($dados['usuario_os'] ?? null) ?: null,
            'usuario_site'          => ($dados['usuario_site'] ?? null) ?: null,
            'database'              => ($dados['database'] ?? null) ?: null,
            'usuario_db'            => ($dados['usuario_db'] ?? null) ?: null,
            'caminho'               => ($dados['caminho'] ?? null) ?: null,
            'git'                   => ($dados['git'] ?? null) ?: null,
            'empresa_desenvolvedor' => ($dados['empresa_desenvolvedor'] ?? null) ?: null,
            'responsavel_diretor'   => ($dados['responsavel_diretor'] ?? null) ?: null,
        ];

        foreach (self::CAMPOS_SENHA as $campo) {
            if (!empty($dados[$campo])) {
                $prepared[$campo] = Crypt::encryptString($dados[$campo]);
            }
        }

        return $prepared;
    }
}
