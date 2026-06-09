@extends('layouts.app')
@section('title', 'Nova Aplicação')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Nova Aplicação</h4>
    <a href="{{ route('aplicacoes.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Voltar
    </a>
</div>

<form method="POST" action="{{ route('aplicacoes.store') }}">
@csrf

    {{-- ── Dados básicos ──────────────────────────────────────────────── --}}
    <div class="card mb-3">
        <div class="card-header"><strong>Dados Básicos</strong></div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="nome_aplicacao" class="form-label">
                        Nome da Aplicação <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="nome_aplicacao" name="nome_aplicacao"
                           class="form-control @error('nome_aplicacao') is-invalid @enderror"
                           value="{{ old('nome_aplicacao') }}" required autofocus>
                    @error('nome_aplicacao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="ip" class="form-label">IP</label>
                    <input type="text" id="ip" name="ip"
                           class="form-control @error('ip') is-invalid @enderror"
                           value="{{ old('ip') }}" placeholder="192.168.0.1">
                    @error('ip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="ambiente" class="form-label">Ambiente</label>
                    <select id="ambiente" name="ambiente"
                            class="form-select @error('ambiente') is-invalid @enderror">
                        <option value="">— Indefinido —</option>
                        @foreach(\App\Enums\Ambiente::cases() as $amb)
                            <option value="{{ $amb->value }}"
                                    {{ old('ambiente') === $amb->value ? 'selected' : '' }}>
                                {{ $amb->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('ambiente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="so_id" class="form-label">Sistema Operacional</label>
                    <select id="so_id" name="so_id"
                            class="form-select @error('so_id') is-invalid @enderror">
                        <option value="">— Indefinido —</option>
                        @foreach($sistemas as $so)
                            <option value="{{ $so->id }}"
                                    {{ old('so_id') == $so->id ? 'selected' : '' }}>
                                {{ $so->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('so_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="url" class="form-label">URL</label>
                    <input type="text" id="url" name="url"
                           class="form-control @error('url') is-invalid @enderror"
                           value="{{ old('url') }}" placeholder="https://...">
                    @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- ── Acesso SO ────────────────────────────────────────────────────── --}}
    <div class="card mb-3">
        <div class="card-header"><strong>Acesso ao Sistema Operacional</strong></div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="usuario_os" class="form-label">Usuário SO</label>
                    <input type="text" id="usuario_os" name="usuario_os"
                           class="form-control @error('usuario_os') is-invalid @enderror"
                           value="{{ old('usuario_os') }}" autocomplete="off">
                    @error('usuario_os')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="senha_os" class="form-label">Senha SO</label>
                    <div class="input-group">
                        <input type="password" id="senha_os" name="senha_os"
                               class="form-control @error('senha_os') is-invalid @enderror"
                               maxlength="20" autocomplete="new-password">
                        <button type="button" class="btn btn-outline-secondary toggle-pwd"
                                data-target="senha_os" aria-label="Mostrar/ocultar senha">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <small class="text-muted">Armazenada criptografada.</small>
                    @error('senha_os')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- ── Acesso Aplicação ─────────────────────────────────────────────── --}}
    <div class="card mb-3">
        <div class="card-header"><strong>Acesso à Aplicação</strong></div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="usuario_site" class="form-label">Usuário Aplicação</label>
                    <input type="text" id="usuario_site" name="usuario_site"
                           class="form-control @error('usuario_site') is-invalid @enderror"
                           value="{{ old('usuario_site') }}" autocomplete="off">
                    @error('usuario_site')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="senha_site" class="form-label">Senha Aplicação</label>
                    <div class="input-group">
                        <input type="password" id="senha_site" name="senha_site"
                               class="form-control @error('senha_site') is-invalid @enderror"
                               maxlength="20" autocomplete="new-password">
                        <button type="button" class="btn btn-outline-secondary toggle-pwd"
                                data-target="senha_site" aria-label="Mostrar/ocultar senha">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <small class="text-muted">Armazenada criptografada.</small>
                    @error('senha_site')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- ── Banco de dados ───────────────────────────────────────────────── --}}
    <div class="card mb-3">
        <div class="card-header"><strong>Banco de Dados</strong></div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label for="database" class="form-label">Nome do Banco</label>
                    <input type="text" id="database" name="database"
                           class="form-control @error('database') is-invalid @enderror"
                           value="{{ old('database') }}">
                    @error('database')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="usuario_db" class="form-label">Usuário Banco</label>
                    <input type="text" id="usuario_db" name="usuario_db"
                           class="form-control @error('usuario_db') is-invalid @enderror"
                           value="{{ old('usuario_db') }}" autocomplete="off">
                    @error('usuario_db')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="senha_db" class="form-label">Senha Banco</label>
                    <div class="input-group">
                        <input type="password" id="senha_db" name="senha_db"
                               class="form-control @error('senha_db') is-invalid @enderror"
                               maxlength="20" autocomplete="new-password">
                        <button type="button" class="btn btn-outline-secondary toggle-pwd"
                                data-target="senha_db" aria-label="Mostrar/ocultar senha">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <small class="text-muted">Armazenada criptografada.</small>
                    @error('senha_db')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- ── Informações adicionais ───────────────────────────────────────── --}}
    <div class="card mb-3">
        <div class="card-header"><strong>Informações Adicionais</strong></div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="caminho" class="form-label">Caminho da Aplicação</label>
                    <input type="text" id="caminho" name="caminho"
                           class="form-control @error('caminho') is-invalid @enderror"
                           value="{{ old('caminho') }}" placeholder="/var/www/html/app">
                    @error('caminho')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="git" class="form-label">Repositório Git</label>
                    <input type="text" id="git" name="git"
                           class="form-control @error('git') is-invalid @enderror"
                           value="{{ old('git') }}" placeholder="https://github.com/...">
                    @error('git')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="empresa_desenvolvedor" class="form-label">Empresa / Desenvolvedor</label>
                    <input type="text" id="empresa_desenvolvedor" name="empresa_desenvolvedor"
                           class="form-control @error('empresa_desenvolvedor') is-invalid @enderror"
                           value="{{ old('empresa_desenvolvedor') }}">
                    @error('empresa_desenvolvedor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="responsavel_diretor" class="form-label">Responsável / Diretor</label>
                    <input type="text" id="responsavel_diretor" name="responsavel_diretor"
                           class="form-control @error('responsavel_diretor') is-invalid @enderror"
                           value="{{ old('responsavel_diretor') }}">
                    @error('responsavel_diretor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>


    {{-- ── Stack Tecnológica ──────────────────────────────────────────────── --}}
    <div class="card mb-3">
        <div class="card-header"><strong>Stack Tecnológica</strong></div>
        <div class="card-body">
            <div class="d-flex gap-2 mb-2">
                <input type="text"
                       id="stack-nova"
                       class="form-control"
                       placeholder="Ex: Laravel, React, MySQL, Docker..."
                       autocomplete="off">
                <button type="button" id="stack-adicionar" class="btn btn-secondary">
                    <i class="fas fa-plus" aria-hidden="true"></i> Adicionar
                </button>
            </div>
            <div id="stack-tags" class="d-flex flex-wrap gap-2 mb-1">
                {{-- tags adicionadas via JS --}}
            </div>
            <small class="text-muted">
                Pressione Enter ou clique em Adicionar. Clique no × para remover.
            </small>
        </div>
    </div>

    <div class="d-flex gap-2 mb-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1" aria-hidden="true"></i> Salvar
        </button>
        <a href="{{ route('aplicacoes.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

<script>
// Toggle de visibilidade das senhas no formulário — Vanilla JS
document.querySelectorAll('.toggle-pwd').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var input = document.getElementById(this.dataset.target);
        var icon  = this.querySelector('i');
        if (input.type === 'password') {
            input.type    = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type    = 'password';
            icon.className = 'fas fa-eye';
        }
    });
});
</script>

<script>
// Stack tecnológica — Vanilla JS
(function () {
    var input     = document.getElementById('stack-nova');
    var btn       = document.getElementById('stack-adicionar');
    var container = document.getElementById('stack-tags');

    function addTag(nome) {
        nome = nome.trim();
        if (!nome) return;
        var existing = Array.from(container.querySelectorAll('input[name="tecnologias[]"]'))
            .map(function (i) { return i.value.toLowerCase(); });
        if (existing.indexOf(nome.toLowerCase()) !== -1) return;

        var tag = document.createElement('span');
        tag.className = 'badge bg-secondary d-inline-flex align-items-center gap-1 px-2 py-1 stack-tag';
        tag.style.fontSize = '.85rem';
        tag.innerHTML = nome.replace(/</g,'&lt;').replace(/>/g,'&gt;') +
            '<input type="hidden" name="tecnologias[]" value="' + nome.replace(/"/g,'&quot;') + '">' +
            '<button type="button" class="btn-close btn-close-white ms-1" style="font-size:.55rem" aria-label="Remover"></button>';
        tag.querySelector('.btn-close').addEventListener('click', function () { tag.remove(); });
        container.appendChild(tag);
        input.value = '';
        input.focus();
    }

    btn.addEventListener('click', function () { addTag(input.value); });
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); addTag(this.value); }
    });

    // Botões de fechar nos tags pré-preenchidos (edição)
    container.querySelectorAll('.btn-close').forEach(function (b) {
        b.addEventListener('click', function () { b.closest('.stack-tag').remove(); });
    });
}());
</script>
@endsection
