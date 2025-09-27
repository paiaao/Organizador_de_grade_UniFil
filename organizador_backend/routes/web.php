<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AlunoController;

Route::get('/', function () {
    return view('welcome'); //pagina do laravel
});

//Route::get('/alunos', [AlunoController::class, 'index']);   //rotas comentadas para teste, antes o api.php nao funcionava
//Route::post('/alunos', [AlunoController::class, 'store']);
//Route::put('/alunos/{id}', [AlunoController::class, 'update']);
//Route::delete('/alunos/{id}', [AlunoController::class, 'destroy']);