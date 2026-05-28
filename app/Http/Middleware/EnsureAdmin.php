<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\Role;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role !== Role::Administrador) {
            abort(403, 'Acesso restrito a administradores.');
        }

        return $next($request);
    }
}