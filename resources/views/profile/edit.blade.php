@extends('layouts.app')
@section('title', 'Meu Perfil')

@section('content')
<div class="row g-4" style="max-width: 700px;">

    {{-- Atualizar dados --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">Informações do Perfil</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label" for="name">Nome</label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">E-mail</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar</button>

                    @if(session('status') === 'profile-updated')
                        <span class="text-success ms-2">Salvo com sucesso.</span>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Alterar senha --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">Alterar Senha</div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="current_password">Senha atual</label>
                        <div class="input-group">
                            <input type="password" id="current_password" name="current_password"
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="toggleSenha('current_password')"
                                    aria-label="Mostrar senha atual">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="new_password">Nova senha</label>
                        <div class="input-group">
                            <input type="password" id="new_password" name="password"
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="toggleSenha('new_password')"
                                    aria-label="Mostrar nova senha">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">Confirmar nova senha</label>
                        <div class="input-group">
                            <input type="password" id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control">
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="toggleSenha('password_confirmation')"
                                    aria-label="Mostrar confirmação">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Alterar Senha</button>

                    @if(session('status') === 'password-updated')
                        <span class="text-success ms-2">Senha alterada com sucesso.</span>
                    @endif
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function toggleSenha(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection