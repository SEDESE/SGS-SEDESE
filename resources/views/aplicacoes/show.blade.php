@extends('layouts.app')
@section('title', $aplicacao->nome_aplicacao)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>{{ $aplicacao->nome_aplicacao }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('aplicacoes.edit', $aplicacao->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1" aria-hidden="true"></i> Editar
        </a>
        <a href="{{ route('aplicacoes.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">

            {{-- Dados básicos --}}
            <div class="col-12">
                <h6 class="text-muted border-bottom pb-1">Dados Básicos</h6>
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
                @else <span>—</span> @endif
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block">Sistema Operacional</small>
                <span>{{ $aplicacao->sistemaOperacional?->nome ?? '—' }}</span>
            </div>

            {{-- Acesso SO --}}
            <div class="col-12 mt-2">
                <h6 class="text-muted border-bottom pb-1">Acesso ao SO</h6>
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
                    <button type="button" class="btn btn-sm btn-link toggle-senha p-0 ms-1"
                            data-senha="{{ $aplicacao->senhaOsDecryptada() }}"
                            aria-label="Mostrar senha SO">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                    @endif
                @else <span>—</span> @endif
            </div>

            {{-- Acesso Aplicação --}}
            <div class="col-12 mt-2">
                <h6 class="text-muted border-bottom pb-1">Acesso à Aplicação</h6>
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
                    <button type="button" class="btn btn-sm btn-link toggle-senha p-0 ms-1"
                            data-senha="{{ $aplicacao->senhaSiteDecryptada() }}"
                            aria-label="Mostrar senha aplicação">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                    @endif
                @else <span>—</span> @endif
            </div>

            {{-- Banco de dados --}}
            <div class="col-12 mt-2">
                <h6 class="text-muted border-bottom pb-1">Banco de Dados</h6>
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
                    <button type="button" class="btn btn-sm btn-link toggle-senha p-0 ms-1"
                            data-senha="{{ $aplicacao->senhaDbDecryptada() }}"
                            aria-label="Mostrar senha banco">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                    @endif
                @else <span>—</span> @endif
            </div>

            {{-- Informações adicionais --}}
            <div class="col-12 mt-2">
                <h6 class="text-muted border-bottom pb-1">Informações Adicionais</h6>
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
            <div class="col-12">
                <small class="text-muted d-block">Stack Tecnológica</small>
                @if($aplicacao->tecnologias->isEmpty())
                    <span class="text-muted">—</span>
                @else
                    <div class="d-flex flex-wrap gap-1 mt-1">
                        @foreach($aplicacao->tecnologias as $tech)
                            <span class="badge bg-secondary">{{ $tech->nome }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block">Cadastrado em</small>
                <span>{{ $aplicacao->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block">Última atualização</small>
                <span>{{ $aplicacao->updated_at->format('d/m/Y H:i') }}</span>
            </div>

        </div>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-senha').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var display = this.parentElement.querySelector('.senha-display');
        var icon    = this.querySelector('i');
        if (display.textContent === '••••••') {
            display.textContent = this.dataset.senha;
            icon.className      = 'fas fa-eye-slash';
        } else {
            display.textContent = '••••••';
            icon.className      = 'fas fa-eye';
        }
    });
});
</script>
@endsection
