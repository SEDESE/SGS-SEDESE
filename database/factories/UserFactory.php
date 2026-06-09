<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'role'              => Role::Operador,   // padrão: operador
            'ativo'             => true,
            'remember_token'    => Str::random(10),
        ];
    }

    /** Usuário com role Administrador. */
    public function admin(): static
    {
        return $this->state(['role' => Role::Administrador]);
    }

    /** Usuário com role Operador (estado explícito). */
    public function operador(): static
    {
        return $this->state(['role' => Role::Operador]);
    }

    /** Usuário desativado. */
    public function inativo(): static
    {
        return $this->state(['ativo' => false]);
    }

    /** E-mail não verificado. */
    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }
}
