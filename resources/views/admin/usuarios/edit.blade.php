@extends('layouts.app')
@section('title', 'Editar Usuário')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">Editar Usuário</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label" for="name">Nome</label>
                <input type="text" id="name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $usuario->name) }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $usuario->email) }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="role">Role</label>
                <select id="role" name="role"
                        class="form-select @error('role') is-invalid @enderror">
                    @foreach($roles as $role)
                        <option value="{{ $role->value }}"
                            {{ old('role', $usuario->role->value) === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- ── Redefinir Senha ──────────────────────────────────────────── --}}
            <hr class="my-4">
            <h6 class="text-muted mb-3">
                <i class="fas fa-key me-1" aria-hidden="true"></i>
                Redefinir Senha
            </h6>
            <p class="text-muted small">Deixe em branco para manter a senha atual.</p>

            <div class="mb-3">
                <label class="form-label" for="nova_senha">Nova Senha</label>
                <input type="password"
                       id="nova_senha"
                       name="nova_senha"
                       class="form-control @error('nova_senha') is-invalid @enderror"
                       autocomplete="new-password">
                <small class="text-muted">Mínimo 8 caracteres, letras maiúsculas, minúsculas e números.</small>
                @error('nova_senha')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="nova_senha_confirmation">Confirmar Nova Senha</label>
                <input type="password"
                       id="nova_senha_confirmation"
                       name="nova_senha_confirmation"
                       class="form-control"
                       autocomplete="new-password">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection