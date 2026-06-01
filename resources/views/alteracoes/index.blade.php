@extends('layouts.app')
@section('title', 'Histórico de Alterações')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Histórico de Alterações</h4>
</div>

{{-- Busca --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('historico.index') }}" class="d-flex gap-2">
            <input type="text"
                   name="busca"
                   class="form-control form-control-sm"
                   placeholder="Buscar por descrição, usuário, aplicação ou data (dd/mm ou dd/mm/aaaa)..."
                   value="{{ $filtros['busca'] ?? '' }}">
            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
            @if(!empty($filtros['busca']))
            <a href="{{ route('historico.index') }}" class="btn btn-outline-secondary btn-sm">
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
                        <th style="width:140px">Data / Hora</th>
                        <th style="width:160px">Usuário</th>
                        <th style="width:180px">Aplicação</th>
                        <th>Descrição</th>
                        <th style="width:90px">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alteracoes as $alteracao)
                    <tr>
                        {{-- Data no fuso America/Sao_Paulo — RF-05.2 --}}
                        <td class="text-nowrap small">
                            {{ $alteracao->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            {{ $alteracao->usuario?->name ?? '(usuário removido)' }}
                        </td>
                        <td>
                            @if($alteracao->aplicacao)
                                <a href="{{ route('aplicacoes.show', $alteracao->aplicacao->id) }}"
                                   class="text-decoration-none">
                                    {{ $alteracao->aplicacao->nome_aplicacao }}
                                </a>
                            @else
                                <span class="text-muted fst-italic">Aplicação excluída</span>
                            @endif
                        </td>
                        <td class="small">{{ $alteracao->descricao }}</td>
                        <td>
                            {{-- Editar: autor ou admin — RF-05.5 --}}
                            @if(auth()->user()->isAdmin() || $alteracao->user_id === auth()->id())
                            <a href="{{ route('historico.edit', $alteracao->id) }}"
                               class="btn btn-primary btn-sm"
                               aria-label="Editar registro">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </a>
                            @endif

                            {{-- Excluir: somente admin — RF-05.6 --}}
                            @if(auth()->user()->isAdmin())
                            <form method="POST"
                                  action="{{ route('historico.destroy', $alteracao->id) }}"
                                  class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Excluir este registro de histórico?')"
                                        aria-label="Excluir registro">
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
                                Nenhum registro encontrado para "{{ $filtros['busca'] }}".
                            @else
                                Nenhum registro de histórico ainda.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $alteracoes->links() }}</div>
@endsection
