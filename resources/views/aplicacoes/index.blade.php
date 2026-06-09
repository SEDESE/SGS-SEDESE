@extends('layouts.app')
@section('title', 'Aplicações')

@section('content')
{{-- Cabeçalho --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Aplicações</h4>
    <a href="{{ route('aplicacoes.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1" aria-hidden="true"></i> Nova Aplicação
    </a>
</div>

{{-- Busca --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('aplicacoes.index') }}" class="d-flex gap-2">
            @if(($filtros['sort'] ?? false))
                <input type="hidden" name="sort"      value="{{ $filtros['sort'] }}">
                <input type="hidden" name="direction" value="{{ $filtros['direction'] ?? 'asc' }}">
            @endif
            <input type="text"
                   name="busca"
                   class="form-control form-control-sm"
                   placeholder="Buscar por nome, IP, URL, usuário, banco..."
                   value="{{ $filtros['busca'] ?? '' }}">
            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
            @if(!empty($filtros['busca']))
            <a href="{{ route('aplicacoes.index', array_filter(['sort' => $filtros['sort'] ?? null, 'direction' => $filtros['direction'] ?? null])) }}"
               class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times" aria-hidden="true"></i>
            </a>
            @endif
        </form>
    </div>
</div>

{{-- Tabela --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-light mb-0">
                <thead>
                    <tr>
                        @php
                            $sort      = $filtros['sort']      ?? 'nome_aplicacao';
                            $direction = $filtros['direction']  ?? 'asc';
                            $busca     = $filtros['busca']      ?? '';

                            $sortLink = function(string $col, string $label, string $sort, string $direction, string $busca): string {
                                $nextDir = ($sort === $col && $direction === 'asc') ? 'desc' : 'asc';
                                $icon = $sort === $col
                                    ? ($direction === 'asc' ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>')
                                    : ' <i class="fas fa-sort text-muted"></i>';
                                $params = http_build_query(array_filter(['sort' => $col, 'direction' => $nextDir, 'busca' => $busca]));
                                return "<a href=\"?" . $params . "\" class=\"text-decoration-none text-dark\">{$label}{$icon}</a>";
                            }
                        @endphp
                        <th>{!! $sortLink('nome_aplicacao', 'Nome', $sort, $direction, $busca) !!}</th>
                        <th>{!! $sortLink('ip',             'IP',   $sort, $direction, $busca) !!}</th>
                        <th>{!! $sortLink('ambiente',       'Ambiente', $sort, $direction, $busca) !!}</th>
                        <th>{!! $sortLink('url',            'URL',  $sort, $direction, $busca) !!}</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aplicacoes as $aplicacao)
                    <tr>
                        <td>{{ $aplicacao->nome_aplicacao }}</td>
                        <td>{{ $aplicacao->ip ?? '—' }}</td>
                        <td>
                            @if($aplicacao->ambiente)
                                @php
                                    $badgeAmbiente = match($aplicacao->ambiente) {
                                        \App\Enums\Ambiente::Producao      => 'bg-danger',
                                        \App\Enums\Ambiente::Homologacao   => 'bg-warning text-dark',
                                        \App\Enums\Ambiente::Desenvolvimento => 'bg-info text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $badgeAmbiente }}">
                                    {{ $aplicacao->ambiente->label() }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($aplicacao->url)
                                <a href="{{ $aplicacao->url }}" target="_blank" rel="noopener"
                                   class="text-truncate d-inline-block" style="max-width:180px">
                                    {{ $aplicacao->url }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            {{-- Detalhes --}}
                            <button type="button"
                                    class="btn btn-info btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-{{ $aplicacao->id }}"
                                    aria-label="Ver detalhes de {{ $aplicacao->nome_aplicacao }}">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>

                            {{-- Editar --}}
                            <a href="{{ route('aplicacoes.edit', $aplicacao->id) }}"
                               class="btn btn-primary btn-sm"
                               aria-label="Editar {{ $aplicacao->nome_aplicacao }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </a>

                            {{-- Excluir — somente Admin --}}
                            @if(auth()->user()->isAdmin())
                            <form method="POST"
                                  action="{{ route('aplicacoes.destroy', $aplicacao->id) }}"
                                  class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Excluir a aplicação \"{{ addslashes($aplicacao->nome_aplicacao) }}\"? Esta ação não pode ser desfeita.')"
                                        aria-label="Excluir {{ $aplicacao->nome_aplicacao }}">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            @if(!empty($filtros['busca']))
                                Nenhuma aplicação encontrada para "{{ $filtros['busca'] }}".
                            @else
                                Nenhuma aplicação cadastrada.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $aplicacoes->links() }}</div>

{{-- ═══════════════════════════════════════════════════════════════
     Modais de detalhes — um por linha da página atual (RF-04.4)
     ════════════════════════════════════════════════════════════ --}}
@foreach($aplicacoes as $aplicacao)
<div class="modal fade"
     id="modal-{{ $aplicacao->id }}"
     tabindex="-1"
     aria-labelledby="modalLabel-{{ $aplicacao->id }}"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel-{{ $aplicacao->id }}">
                    <i class="fas fa-server me-2 text-primary" aria-hidden="true"></i>
                    {{ $aplicacao->nome_aplicacao }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    {{-- Dados básicos --}}
                    <div class="col-12">
                        <h6 class="text-muted border-bottom pb-1 mb-2">Dados básicos</h6>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">IP</small>
                        <span>{{ $aplicacao->ip ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Ambiente</small>
                        <span>{{ $aplicacao->ambiente?->label() ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">URL</small>
                        @if($aplicacao->url)
                            <a href="{{ $aplicacao->url }}" target="_blank" rel="noopener">{{ $aplicacao->url }}</a>
                        @else
                            <span>—</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Sistema Operacional</small>
                        <span>{{ $aplicacao->sistemaOperacional?->nome ?? '—' }}</span>
                    </div>

                    {{-- Acesso SO --}}
                    <div class="col-12 mt-1">
                        <h6 class="text-muted border-bottom pb-1 mb-2">Acesso ao SO</h6>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Usuário SO</small>
                        <span>{{ $aplicacao->usuario_os ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Senha SO</small>
                        @if($aplicacao->senha_os)
                            <span class="senha-display">••••••</span>
                            @if(auth()->user()->isAdmin())
                            <button type="button"
                                    class="btn btn-sm btn-link toggle-senha p-0 ms-1"
                                    data-senha="{{ $aplicacao->senhaOsDecryptada() }}"
                                    aria-label="Mostrar senha SO">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                            @endif
                        @else
                            <span>—</span>
                        @endif
                    </div>

                    {{-- Acesso aplicação --}}
                    <div class="col-12 mt-1">
                        <h6 class="text-muted border-bottom pb-1 mb-2">Acesso à Aplicação</h6>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Usuário Aplicação</small>
                        <span>{{ $aplicacao->usuario_site ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Senha Aplicação</small>
                        @if($aplicacao->senha_site)
                            <span class="senha-display">••••••</span>
                            @if(auth()->user()->isAdmin())
                            <button type="button"
                                    class="btn btn-sm btn-link toggle-senha p-0 ms-1"
                                    data-senha="{{ $aplicacao->senhaSiteDecryptada() }}"
                                    aria-label="Mostrar senha aplicação">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                            @endif
                        @else
                            <span>—</span>
                        @endif
                    </div>

                    {{-- Banco de dados --}}
                    <div class="col-12 mt-1">
                        <h6 class="text-muted border-bottom pb-1 mb-2">Banco de Dados</h6>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Nome do Banco</small>
                        <span>{{ $aplicacao->database ?? '—' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Usuário Banco</small>
                        <span>{{ $aplicacao->usuario_db ?? '—' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Senha Banco</small>
                        @if($aplicacao->senha_db)
                            <span class="senha-display">••••••</span>
                            @if(auth()->user()->isAdmin())
                            <button type="button"
                                    class="btn btn-sm btn-link toggle-senha p-0 ms-1"
                                    data-senha="{{ $aplicacao->senhaDbDecryptada() }}"
                                    aria-label="Mostrar senha banco">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                            @endif
                        @else
                            <span>—</span>
                        @endif
                    </div>

                    {{-- Informações adicionais --}}
                    <div class="col-12 mt-1">
                        <h6 class="text-muted border-bottom pb-1 mb-2">Informações Adicionais</h6>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Caminho da Aplicação</small>
                        <span class="text-break">{{ $aplicacao->caminho ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Repositório Git</small>
                        <span class="text-break">{{ $aplicacao->git ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Empresa / Desenvolvedor</small>
                        <span>{{ $aplicacao->empresa_desenvolvedor ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Responsável / Diretor</small>
                        <span>{{ $aplicacao->responsavel_diretor ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Cadastrado em</small>
                        <span>{{ $aplicacao->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Última atualização</small>
                        <span>{{ $aplicacao->updated_at->format('d/m/Y H:i') }}</span>
                    </div>

                </div>{{-- /row --}}
            </div>{{-- /modal-body --}}

            <div class="modal-footer">
                <a href="{{ route('aplicacoes.edit', $aplicacao->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit me-1" aria-hidden="true"></i> Editar
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Fechar
                </button>
            </div>

        </div>
    </div>
</div>
@endforeach

<script>
// Toggle de senhas nos modais — Vanilla JS (RNF-06.7.2)
document.querySelectorAll('.toggle-senha').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var display = this.parentElement.querySelector('.senha-display');
        var icon    = this.querySelector('i');
        if (display.textContent === '••••••') {
            display.textContent  = this.dataset.senha;
            icon.className       = 'fas fa-eye-slash';
        } else {
            display.textContent  = '••••••';
            icon.className       = 'fas fa-eye';
        }
    });
});
</script>
@endsection
