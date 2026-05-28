<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function listar()
    {
        return User::orderBy('name')->paginate(20);
    }

    public function criar(array $dados): User
    {
        return User::create([
            'name'     => $dados['name'],
            'email'    => $dados['email'],
            'password' => Hash::make($dados['password']),
            'role'     => Role::from($dados['role']),
            'ativo'    => true,
        ]);
    }

    public function atualizar(User $user, array $dados): void
    {
        $user->update([
            'name'  => $dados['name'],
            'email' => $dados['email'],
            'role'  => Role::from($dados['role']),
        ]);
    }

    public function alterarSenha(User $user, string $novaSenha): void
    {
        $user->update([
            'password' => Hash::make($novaSenha),
        ]);
    }

    public function desativar(User $user): void
    {
        $user->update(['ativo' => false]);
    }

    public function excluir(User $user): void
    {
        $user->delete();
    }
}