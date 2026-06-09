<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AccessLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        // Gravar log de login
        AccessLog::create([
            'user_id' => $user->id,
            'evento'  => 'login',
            'ip'      => $request->ip(),
        ]);

        // Atualizar last_login
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Gravar log de logout antes de sair
        AccessLog::create([
            'user_id' => $request->user()->id,
            'evento'  => 'logout',
            'ip'      => $request->ip(),
        ]);

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}