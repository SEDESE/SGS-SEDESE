<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsuarioRequest;
use App\Http\Requests\Admin\UpdateUsuarioRequest;
use App\Models\User;
use App\Services\UsuarioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function __construct(private UsuarioService $service) {}

    public function index(): View
    {
        $usuarios = $this->service->listar();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create(): View
    {
        $roles = Role::cases();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(StoreUsuarioRequest $request): RedirectResponse
    {
        $this->service->criar($request->validated());
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $usuario): View
    {
        $roles = Role::cases();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(UpdateUsuarioRequest $request, User $usuario): RedirectResponse
    {
        $this->service->atualizar($usuario, $request->validated());
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        $this->service->excluir($usuario);
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuário removido com sucesso.');
    }

    public function desativar(User $usuario): RedirectResponse
    {
        $this->service->desativar($usuario);
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuário desativado.');
    }
}