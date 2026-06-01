<?php

use App\Http\Controllers\AplicacaoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SistemaOperacionalController;
use App\Http\Controllers\Admin\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inventário de aplicações — visível para todos os autenticados (RF-04)
    Route::resource('aplicacoes', AplicacaoController::class);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::patch('usuarios/{usuario}/desativar', [UsuarioController::class, 'desativar'])
        ->name('usuarios.desativar');

    Route::resource('sistemas-operacionais', SistemaOperacionalController::class);
    Route::patch('sistemas-operacionais/{sistema_operacional}/desativar', [SistemaOperacionalController::class, 'desativar'])
        ->name('sistemas-operacionais.desativar');
    Route::patch('sistemas-operacionais/{sistema_operacional}/ativar', [SistemaOperacionalController::class, 'ativar'])
        ->name('sistemas-operacionais.ativar');
});

require __DIR__.'/auth.php';
