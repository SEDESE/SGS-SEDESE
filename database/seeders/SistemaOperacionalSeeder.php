<?php

namespace Database\Seeders;

use App\Enums\FamiliaOS;
use App\Models\SistemaOperacional;
use Illuminate\Database\Seeder;

class SistemaOperacionalSeeder extends Seeder
{
    public function run(): void
    {
        $sistemas = [
            ['nome' => 'CentOS release 6.8', 'familia' => FamiliaOS::Linux],
            ['nome' => 'Ubuntu',              'familia' => FamiliaOS::Linux],
            ['nome' => 'Windows',             'familia' => FamiliaOS::Windows],
            ['nome' => 'Outro Linux',         'familia' => FamiliaOS::Linux],
        ];

        foreach ($sistemas as $dados) {
            SistemaOperacional::firstOrCreate(
                ['nome' => $dados['nome']],
                ['familia' => $dados['familia'], 'ativo' => true]
            );
        }
    }
}
