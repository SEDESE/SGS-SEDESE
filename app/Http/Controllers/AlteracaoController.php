<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAlteracaoRequest;
use App\Models\Alteracao;
use App\Services\AlteracaoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlteracaoController extends Controller
{
    public function __construct(private AlteracaoService $service) {}

    public function index(Request $request): View
    {
        $filtros    = $request->only(['busca']);
        $alteracoes = $this->service->listar($filtros);

        return view('alteracoes.index', compact('alteracoes', 'filtros'));
    }

    public function edit(Alteracao $alteracao): View
    {
        // Edição restrita ao autor ou Administrador — RF-05.5
        if (!auth()->user()->isAdmin() && $alteracao->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para editar este registro.');
        }

        // Eager load para evitar lazy loads em $alteracao->usuario e $alteracao->aplicacao na view — RNF-04.3
        $alteracao->loadMissing(['usuario', 'aplicacao']);

        return view('alteracoes.edit', compact('alteracao'));
    }

    public function update(UpdateAlteracaoRequest $request, Alteracao $alteracao): RedirectResponse
    {
        // Mesma regra de autorização do edit
        if (!auth()->user()->isAdmin() && $alteracao->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para editar este registro.');
        }

        $this->service->atualizar($alteracao, $request->validated());

        return redirect()->route('historico.index')
            ->with('success', 'Registro de histórico atualizado.');
    }

    public function destroy(Alteracao $alteracao): RedirectResponse
    {
        // Exclusão restrita a Administradores — RF-05.6
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas Administradores podem excluir registros de histórico.');
        }

        $this->service->excluir($alteracao);

        return redirect()->route('historico.index')
            ->with('success', 'Registro de histórico excluído.');
    }
}
