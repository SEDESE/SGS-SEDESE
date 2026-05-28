@extends('layouts.app')
@section('title', 'Usuários')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Usuários</h4>
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1" aria-hidden="true"></i> Novo Usuário
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-light mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->role->label() }}</td>
                        <td>
                            @if($usuario->ativo)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.usuarios.edit', $usuario) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </a>
                            @if($usuario->ativo)
                            <form method="POST"
                                  action="{{ route('admin.usuarios.desativar', $usuario) }}"
                                  class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-warning btn-sm"
                                        onclick="return confirm('Desativar este usuário?')"
                                        aria-label="Desativar usuário">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            </form>
                            @endif
                            <form method="POST"
                                  action="{{ route('admin.usuarios.destroy', $usuario) }}"
                                  class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Excluir permanentemente?')"
                                        aria-label="Excluir usuário">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Nenhum usuário cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $usuarios->links() }}
</div>
@endsection