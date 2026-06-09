<?php

namespace Database\Factories;

use App\Models\Aplicacao;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

/**
 * @extends Factory<Aplicacao>
 */
class AplicacaoFactory extends Factory
{
    protected $model = Aplicacao::class;

    public function definition(): array
    {
        return [
            'so_id'                 => null,
            'nome_aplicacao'        => fake()->company() . ' ' . fake()->word(),
            'ip'                    => null,
            'ambiente'              => null,
            'url'                   => null,
            'usuario_os'            => null,
            'senha_os'              => null,
            'usuario_site'          => null,
            'senha_site'            => null,
            'database'              => null,
            'usuario_db'            => null,
            'senha_db'              => null,
            'caminho'               => null,
            'git'                   => null,
            'empresa_desenvolvedor' => null,
            'responsavel_diretor'   => null,
        ];
    }

    /**
     * Aplicação com credenciais criptografadas para testar
     * visibilidade de senhas por role.
     */
    public function withCredentials(): static
    {
        return $this->state([
            'usuario_os' => 'root',
            'senha_os'   => Crypt::encryptString('senha-secreta'),
        ]);
    }
}
