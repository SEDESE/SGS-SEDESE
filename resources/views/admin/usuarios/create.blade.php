@extends('layouts.app')
@section('title', 'Novo Usuário')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">Novo Usuário</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.usuarios.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="name">Nome</label>
                <input type="text" id="name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="role">Role</label>
                <select id="role" name="role"
                        class="form-select @error('role') is-invalid @enderror">
                    <option value="">Selecione...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->value }}"
                            {{ old('role') === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Senha</label>
                <div class="input-group">
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror">
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="toggleSenha('password')"
                            aria-label="Mostrar senha">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Confirmar Senha</label>
                <div class="input-group">
                    <input type="password" id="password_confirmation"
                           name="password_confirmation"
                           class="form-control">
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="toggleSenha('password_confirmation')"
                            aria-label="Mostrar confirmação de senha">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSenha(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection