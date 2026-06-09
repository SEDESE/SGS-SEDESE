<?php

namespace App\Http\Controllers;

use App\Models\Alteracao;
use App\Models\Aplicacao;
use App\Models\SistemaOperacional;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // RF-03.2 — Total de aplicações (query direta, sem colecão em memória)
        $totalAplicacoes = Aplicacao::count();

        // RF-03.3 — Usuários ativos
        $totalUsuarios = User::where('ativo', true)->count();

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
            'totalUsuarios',
            'porAmbiente',
            'porSO',
            'indefinidoCount',
            'ultimasAlteracoes',
        ));
    }
}
