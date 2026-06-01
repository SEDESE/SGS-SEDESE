@extends('layouts.app')
@section('title', 'Novo Sistema Operacional')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Novo Sistema Operacional</h4>
    <a href="{{ route('admin.sistemas-operacionais.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.sistemas-operacionais.store') }}">
            @csrf

            <div class="mb-3">
                <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                <input type="text"
                       id="nome"
                       name="nome"
                       class="form-control @error('nome') is-invalid @enderror"
                       value="{{ old('nome') }}"
                       required
                       autofocus>
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="familia" class="form-label">Família <span class="text-danger">*</span></label>
                <select id="familia"
                        name="familia"
                        class="form-select @error('familia') is-invalid @enderror"
                        required>
                    <option value="">Selecione...</option>
                    @foreach($familias as $familia)
                        <option value="{{ $familia->value }}"
                                {{ old('familia') === $familia->value ? 'selected' : '' }}>
                            {{ $familia->value }}
                        </option>
                    @endforeach
                </select>
                @error('familia')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.sistemas-operacionais.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
