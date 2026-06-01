@extends('layouts.app')
@section('title', 'Sistemas Operacionais')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Sistemas Operacionais</h4>
    <a href="{{ route('admin.sistemas-operacionais.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1" aria-hidden="true"></i> Novo SO
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-light mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Família</th>
                        <th>Status</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sistemas as $so)
                    <tr>
                        <td>{{ $so->nome }}</td>
                        <td>{{ $so->familia->value }}</td>
                        <td>
                            @if($so->ativo)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td>{{ $so->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.sistemas-operacionais.edit', $so->id) }}"
                               class="btn btn-primary btn-sm"
                               aria-label="Editar SO">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </a>

                            @if($so->ativo)
                            <form method="POST"
                                  action="{{ route('admin.sistemas-operacionais.desativar', $so->id) }}"
                                  class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-warning btn-sm"
                                        onclick="return confirm('Desativar este Sistema Operacional?')"
                                        aria-label="Desativar SO">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            </form>
                            @else
                            <form method="POST"
                                  action="{{ route('admin.sistemas-operacionais.ativar', $so->id) }}"
                                  class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-success btn-sm"
                                        onclick="return confirm('Ativar este Sistema Operacional?')"
                                        aria-label="Ativar SO">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                            </form>
                            @endif

                            <form method="POST"
                                  action="{{ route('admin.sistemas-operacionais.destroy', $so->id) }}"
                                  class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Excluir permanentemente? Esta ação não pode ser desfeita.')"
                                        aria-label="Excluir SO">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Nenhum Sistema Operacional cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $sistemas->links() }}
</div>
@endsection
