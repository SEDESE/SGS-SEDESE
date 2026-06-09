<?php

namespace App\Http\Controllers;

use App\Models\Alteracao;
use App\Models\Aplicacao;
use App\Models\SistemaOperacional;
use App\Models\Tecnologia;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // RF-03.2 — Total de aplicações (query direta, sem colecão em memória)
        $totalAplicacoes = Aplicacao::count();

        // Quantitativo por stack tecnológica via LEFT JOIN
        $porStack = Tecnologia::selectRaw('tecnologias.nome, COUNT(aplicacao_tecnologia.aplicacao_id) as total')
            ->leftJoin('aplicacao_tecnologia', 'aplicacao_tecnologia.tecnologia_id', '=', 'tecnologias.id')
            ->groupBy('tecnologias.id', 'tecnologias.nome')
            ->orderByDesc('total')
            ->orderBy('nome')
            ->get();

        // RF-03.4 — Distribuição por ambiente via GROUP BY no banco
        $resultAmbiente = Aplicacao::selectRaw(
                "COALESCE(ambiente, 'Indefinido') as label, COUNT(*) as total"
            )
            ->groupByRaw("COALESCE(ambiente, 'Indefinido')")
            ->pluck('total', 'label')
            ->toArray();

        // Garante que todos os ambientes aparecem, mesmo com contagem zero
        $porAmbiente = array_merge([
            'Producao'        => 0,
            'Homologacao'     => 0,
            'Desenvolvimento' => 0,
            'Indefinido'      => 0,
        ], $resultAmbiente);

        // RF-03.5 / RF-07.6 — Quantitativo por SO via LEFT JOIN (nunca LIKE)
        $porSO = SistemaOperacional::where('sistemas_operacionais.ativo', true)
            ->selectRaw('sistemas_operacionais.nome, COUNT(aplicacoes.id) as total')
            ->leftJoin('aplicacoes', 'aplicacoes.so_id', '=', 'sistemas_operacionais.id')
            ->groupBy('sistemas_operacionais.id', 'sistemas_operacionais.nome')
            ->orderBy('sistemas_operacionais.nome')
            ->get();

        $indefinidoCount = Aplicacao::whereNull('so_id')->count();

        // RF-03.6 — 5 alterações mais recentes com eager loading (sem N+1)
        $ultimasAlteracoes = Alteracao::with(['usuario', 'aplicacao'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalAplicacoes',
            'porStack',
            'porAmbiente',
            'porSO',
            'indefinidoCount',
            'ultimasAlteracoes',
        ));
    }
}
