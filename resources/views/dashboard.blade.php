@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- ── Linha 1: cards simples ──────────────────────────────────────────────── --}}
<div class="row">

    {{-- RF-03.2 Total de Aplicações --}}
    <div class="col-sm-6 col-xl-3">
        <x-small-box
            color="bg-info"
            icon="fas fa-server"
            label="Total de Aplicações"
            :value="$totalAplicacoes"
            :link="route('aplicacoes.index')"
        />
    </div>

    {{-- Stack tecnológica das aplicações--}}
    <div class="col-sm-6 col-xl-3">
        <x-small-box color="bg-success" icon="fas fa-code" label="Stack das Aplicações">
            @if($porStack->isEmpty())
                <p class="mb-1" style="color:rgba(255,255,255,.8);font-size:.85rem">
                    Nenhuma stack cadastrada ainda.
                </p>
            @else
                <ul>
                    @foreach($porStack as $tech)
                    <li>
                        <span>{{ $tech->nome }}</span>
                        <strong>{{ $tech->total }}</strong>
                    </li>
                    @endforeach
                </ul>
            @endif
        </x-small-box>
    </div>

    {{-- RF-03.4 Status por Ambiente --}}
    <div class="col-sm-6 col-xl-3">
        <x-small-box color="bg-warning" icon="fas fa-cloud" label="Status por Ambiente">
            @php
                $labelsAmbiente = [
                    'Producao'        => 'Produção',
                    'Homologacao'     => 'Homologação',
                    'Desenvolvimento' => 'Desenvolvimento',
                    'Indefinido'      => 'Indefinido',
                ];
            @endphp
            <ul>
                @foreach($labelsAmbiente as $key => $nome)
                <li>
                    <span>{{ $nome }}</span>
                    <strong>{{ $porAmbiente[$key] ?? 0 }}</strong>
                </li>
                @endforeach
            </ul>
        </x-small-box>
    </div>

    {{-- RF-03.5 Quantitativo por SO --}}
    <div class="col-sm-6 col-xl-3">
        <x-small-box color="bg-danger" icon="fas fa-desktop" label="Sist. Operacionais">
            <ul>
                @foreach($porSO as $so)
                <li>
                    <span>{{ $so->nome }}</span>
                    <strong>{{ $so->total }}</strong>
                </li>
                @endforeach
                <li>
                    <span>Indefinido</span>
                    <strong>{{ $indefinidoCount }}</strong>
                </li>
            </ul>
        </x-small-box>
    </div>

</div>

{{-- ── Últimas 5 alterações — RF-03.6 ─────────────────────────────────────── --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><i class="fas fa-history me-2" aria-hidden="true"></i>Últimas Alterações</strong>
        <a href="{{ route('historico.index') }}" class="btn btn-outline-secondary btn-sm">
            Ver todas
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-light mb-0">
                <thead>
                    <tr>
                        <th style="width:140px">Data / Hora</th>
                        <th style="width:160px">Usuário</th>
                        <th style="width:180px">Aplicação</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimasAlteracoes as $alt)
                    <tr>
                        <td class="text-nowrap small">
                            {{ $alt->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                        </td>
                        <td>{{ $alt->usuario?->name ?? '(removido)' }}</td>
                        <td>
                            @if($alt->aplicacao)
                                <a href="{{ route('aplicacoes.show', $alt->aplicacao->id) }}"
                                   class="text-decoration-none">
                                    {{ $alt->aplicacao->nome_aplicacao }}
                                </a>
                            @else
                                <span class="text-muted fst-italic">Aplicação excluída</span>
                            @endif
                        </td>
                        <td class="small text-truncate" style="max-width:300px">
                            {{ $alt->descricao }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            Nenhuma alteração registrada ainda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
