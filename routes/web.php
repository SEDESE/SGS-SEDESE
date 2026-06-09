<?php

use App\Http\Controllers\AlteracaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AplicacaoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SistemaOperacionalController;
use App\Http\Controllers\Admin\UsuarioController;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inventário de aplicações — RF-04
    Route::resource('aplicacoes', AplicacaoController::class)
        ->parameters(['aplicacoes' => 'aplicacao']);

    // Histórico de alterações — RF-05
    Route::prefix('historico')->name('historico.')->group(function () {
        Route::get('/',                          [AlteracaoController::class, 'index'])->name('index');
        Route::get('/{alteracao}/edit',          [AlteracaoController::class, 'edit'])->name('edit');
        Route::put('/{alteracao}',               [AlteracaoController::class, 'update'])->name('update');
        Route::delete('/{alteracao}',            [AlteracaoController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::patch('usuarios/{usuario}/desativar', [UsuarioController::class, 'desativar'])
        ->name('usuarios.desativar');

    Route::resource('sistemas-operacionais', SistemaOperacionalController::class)
        ->parameters(['sistemas-operacionais' => 'sistema_operacional']);
    Route::patch('sistemas-operacionais/{sistema_operacional}/desativar', [SistemaOperacionalController::class, 'desativar'])
        ->name('sistemas-operacionais.desativar');
    Route::patch('sistemas-operacionais/{sistema_operacional}/ativar', [SistemaOperacionalController::class, 'ativar'])
        ->name('sistemas-operacionais.ativar');
});

require __DIR__.'/auth.php';
