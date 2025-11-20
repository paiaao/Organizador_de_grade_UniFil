<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\aulaController;
use App\Http\Controllers\horariosController;
use App\Http\Controllers\PosicaoSemanaController;
use App\Http\Controllers\salaController;
use App\Http\Controllers\turmaController;
use App\Http\Controllers\UcController;
use App\Http\Controllers\UsuarioController;
 
Route::resource('aulas', AulaController::class);
Route::resource('horarios', HorariosController::class);
Route::resource('posicaoSemana', PosicaoSemanaController::class);
Route::resource('salas', SalaController::class);
Route::resource('turmas', TurmaController::class);
Route::resource('ucs', UcController::class);
Route::resource('usuarios', UsuarioController::class);

Route::post('/usuarios/login', [UsuarioController::class, 'login']);
