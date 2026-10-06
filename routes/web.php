<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventoController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');
Route::post('/eventos/{id}/perguntas', [EventoController::class, 'storePergunta'])->name('eventos.perguntas.store')->middleware('auth');
Route::delete('/eventos/{id}/perguntas/{pergunta}', [EventoController::class, 'destroyPergunta'])->name('eventos.perguntas.destroy')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
