@extends('layouts.app')
@section('title', 'Editar Registro de Histórico')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Editar Registro de Histórico</h4>
    <a href="{{ route('historico.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">

        {{-- Contexto do registro --}}
        <div class="mb-4 pb-3 border-bottom">
            <div class="row g-2 text-muted small">
                <div class="col-md-4">
                    <strong class="d-block text-dark">Usuário</strong>
                    {{ $alteracao->usuario?->name ?? '—' }}
                </div>
                <div class="col-md-4">
                    <strong class="d-block text-dark">Aplicação</strong>
                    {{ $alteracao->aplicacao?->nome_aplicacao ?? 'Aplicação excluída' }}
                </div>
                <div class="col-md-4">
                    <strong class="d-block text-dark">Data / Hora</strong>
                    {{ $alteracao->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('historico.update', $alteracao->id) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label for="descricao" class="form-label">
                    Descrição <span class="text-danger">*</span>
                </label>
                <textarea id="descricao"
                          name="descricao"
                          rows="5"
                          class="form-control @error('descricao') is-invalid @enderror"
                          required>{{ old('descricao', $alteracao->descricao) }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('historico.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>

    </div>
</div>
@endsection
