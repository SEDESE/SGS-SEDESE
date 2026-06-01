<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FamiliaOS;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSistemaOperacionalRequest;
use App\Http\Requests\Admin\UpdateSistemaOperacionalRequest;
use App\Models\SistemaOperacional;
use App\Services\SistemaOperacionalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SistemaOperacionalController extends Controller
{
    public function __construct(private SistemaOperacionalService $service) {}

    public function index(): View
    {
        $sistemas = $this->service->listar();
        return view('admin.sistemas_operacionais.index', compact('sistemas'));
    }

    public function create(): View
    {
        $familias = FamiliaOS::cases();
        return view('admin.sistemas_operacionais.create', compact('familias'));
    }

    public function store(StoreSistemaOperacionalRequest $request): RedirectResponse
    {
        $this->service->criar($request->validated());
        return redirect()->route('admin.sistemas-operacionais.index')
            ->with('success', 'Sistema Operacional criado com sucesso.');
    }

    public function edit(SistemaOperacional $sistemaOperacional): View
    {
        $familias = FamiliaOS::cases();
        return view('admin.sistemas_operacionais.edit', [
            'so'      => $sistemaOperacional,
            'familias' => $familias,
        ]);
    }

    public function update(UpdateSistemaOperacionalRequest $request, SistemaOperacional $sistemaOperacional): RedirectResponse
    {
        $this->service->atualizar($sistemaOperacional, $request->validated());
        return redirect()->route('admin.sistemas-operacionais.index')
            ->with('success', 'Sistema Operacional atualizado com sucesso.');
    }

    public function destroy(SistemaOperacional $sistemaOperacional): RedirectResponse
    {
        try {
            $this->service->excluir($sistemaOperacional);
            return redirect()->route('admin.sistemas-operacionais.index')
                ->with('success', 'Sistema Operacional excluído com sucesso.');
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.sistemas-operacionais.index')
                ->with('error', $e->getMessage());
        }
    }

    public function desativar(SistemaOperacional $sistemaOperacional): RedirectResponse
    {
        $this->service->desativar($sistemaOperacional);
        return redirect()->route('admin.sistemas-operacionais.index')
            ->with('success', 'Sistema Operacional desativado.');
    }

    public function ativar(SistemaOperacional $sistemaOperacional): RedirectResponse
    {
        $this->service->ativar($sistemaOperacional);
        return redirect()->route('admin.sistemas-operacionais.index')
            ->with('success', 'Sistema Operacional ativado.');
    }
}
