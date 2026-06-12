<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\aulaController;
use App\Http\Controllers\horariosController;
use App\Http\Controllers\posicaoSemanaController;
use App\Http\Controllers\salaController;
use App\Http\Controllers\turmaController;
use App\Http\Controllers\ucController;
use App\Http\Controllers\usuarioController;
 
Route::resource('aulas', aulaController::class);
Route::resource('horarios', horariosController::class);
Route::resource('posicaoSemana', posicaoSemanaController::class);
Route::resource('salas', salaController::class);
Route::resource('turmas', turmaController::class);
Route::resource('ucs', ucController::class);
Route::resource('usuarios', usuarioController::class);
Route::get('horarios/por-turma/{turmaId}', [HorariosController::class, 'porTurma']);

Route::post('/usuarios/login', [UsuarioController::class, 'login']);
Route::get('horarios/por-professor/{professorId}', [HorariosController::class, 'porProfessor']);