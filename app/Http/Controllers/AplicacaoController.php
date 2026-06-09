<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAplicacaoRequest;
use App\Http\Requests\UpdateAplicacaoRequest;
use App\Models\Aplicacao;
use App\Services\AplicacaoService;
use App\Services\SistemaOperacionalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AplicacaoController extends Controller
{
    public function __construct(
        private AplicacaoService $service,
        private SistemaOperacionalService $soService,
    ) {}

    public function index(Request $request): View
    {
        $filtros = $request->only(['busca', 'sort', 'direction']);
        $aplicacoes = $this->service->listar($filtros);

        return view('aplicacoes.index', compact('aplicacoes', 'filtros'));
    }

    public function create(): View
    {
        $sistemas = $this->soService->ativos();
        return view('aplicacoes.create', compact('sistemas'));
    }

    public function store(StoreAplicacaoRequest $request): RedirectResponse
    {
        $this->service->criar($request->validated());

        return redirect()->route('aplicacoes.index')
            ->with('success', 'Aplicação cadastrada com sucesso.');
    }

    public function show(Aplicacao $aplicacao): View
    {
        // Eager load para evitar lazy load em $aplicacao->sistemaOperacional na view — RNF-04.3
        $aplicacao->loadMissing('sistemaOperacional');
        $sistemas = $this->soService->ativos();
        return view('aplicacoes.show', compact('aplicacao', 'sistemas'));
    }

    public function edit(Aplicacao $aplicacao): View
    {
        $sistemas = $this->soService->ativos();
        return view('aplicacoes.edit', compact('aplicacao', 'sistemas'));
    }

    public function update(UpdateAplicacaoRequest $request, Aplicacao $aplicacao): RedirectResponse
    {
        $this->service->atualizar($aplicacao, $request->validated());

        return redirect()->route('aplicacoes.index')
            ->with('success', 'Aplicação atualizada com sucesso.');
    }

    public function destroy(Aplicacao $aplicacao): RedirectResponse
    {
        // Operadores não podem excluir — RF-01.4
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Apenas Administradores podem excluir aplicações.');
        }

        $this->service->excluir($aplicacao);

        return redirect()->route('aplicacoes.index')
            ->with('success', 'Aplicação excluída com sucesso.');
    }
}
